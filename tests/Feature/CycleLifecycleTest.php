<?php

namespace Tests\Feature;

use App\Models\Cycle;
use App\Models\FeedBrand;
use App\Models\Pond;
use App\Models\Provider;
use App\Models\Species;
use Tests\TestCase;

final class CycleLifecycleTest extends TestCase
{
    public function test_stocking_opens_a_cycle(): void
    {
        $pond = Pond::factory()->create();
        $species = Species::factory()->create();
        $provider = Provider::factory()->create();

        $response = $this->actingAs($pond->user)->post('/estanques/'.$pond->id.'/siembra', [
            'species_id' => $species->id, 'provider_id' => $provider->id, 'stocked_fish' => 2500,
            'initial_weight_grams' => '1,5', 'stocked_on' => now()->subDays(3)->toDateString(),
        ]);

        $cycle = Cycle::query()->sole();
        $response->assertRedirect('/ciclos/'.$cycle->id);
        $this->assertTrue($cycle->isOpen());
        $this->assertSame('1.50', $cycle->initial_weight_grams);
        $this->assertSame($provider->id, $cycle->provider_id);
    }

    public function test_a_pond_holds_one_open_cycle_at_a_time(): void
    {
        $cycle = $this->openCycle();

        $this->actingAs($cycle->pond->user)->post('/estanques/'.$cycle->pond_id.'/siembra', [
            'species_id' => $cycle->species_id, 'stocked_fish' => 100, 'initial_weight_grams' => 1,
            'stocked_on' => now()->toDateString(),
        ])->assertSessionHasErrors('stocked_on');
        $this->assertSame(1, Cycle::query()->count());
    }

    public function test_stocking_cannot_predate_the_previous_close(): void
    {
        $closed = Cycle::factory()->closed()->create();

        $this->actingAs($closed->pond->user)->post('/estanques/'.$closed->pond_id.'/siembra', [
            'species_id' => $closed->species_id, 'stocked_fish' => 100, 'initial_weight_grams' => 1,
            'stocked_on' => now()->subMonth()->toDateString(),
        ])->assertSessionHasErrors('stocked_on');

        $this->actingAs($closed->pond->user)->post('/estanques/'.$closed->pond_id.'/siembra', [
            'species_id' => $closed->species_id, 'stocked_fish' => 100, 'initial_weight_grams' => 1,
            'stocked_on' => $closed->closed_on?->toDateString(),
        ])->assertSessionHasNoErrors();
    }

    public function test_inactive_ponds_cannot_be_stocked(): void
    {
        $pond = Pond::factory()->create(['active' => false]);

        $this->actingAs($pond->user)->get('/estanques/'.$pond->id.'/siembra')->assertForbidden();
    }

    public function test_feedings_snapshot_the_protein_of_the_feed(): void
    {
        $cycle = $this->openCycle();
        $brand = FeedBrand::factory()->create(['protein_percent' => 38]);

        $this->actingAs($cycle->pond->user)->post('/ciclos/'.$cycle->id.'/alimentaciones', [
            'fed_on' => now()->toDateString(), 'feed_brand_id' => $brand->id, 'kilograms' => '12,5',
        ])->assertRedirect('/ciclos/'.$cycle->id.'#alimentacion');

        $brand->update(['protein_percent' => 45]);
        $feeding = $cycle->feedings()->sole();
        $this->assertSame('12.50', $feeding->kilograms);
        $this->assertSame('38.0', $feeding->protein_percent);
    }

    public function test_entry_dates_must_fall_between_stocking_and_today(): void
    {
        $cycle = $this->openCycle();
        $brand = FeedBrand::factory()->create();
        $post = fn (string $date) => $this->actingAs($cycle->pond->user)->post('/ciclos/'.$cycle->id.'/alimentaciones', [
            'fed_on' => $date, 'feed_brand_id' => $brand->id, 'kilograms' => 5,
        ]);

        $post($cycle->stocked_on->subDay()->toDateString())->assertSessionHasErrorsIn('feeding', 'fed_on');
        $post(now()->addDay()->toDateString())->assertSessionHasErrorsIn('feeding', 'fed_on');
        $this->assertSame(0, $cycle->feedings()->count());
    }

    public function test_samplings_update_weight_and_population(): void
    {
        $cycle = $this->openCycle();

        $this->actingAs($cycle->pond->user)->post('/ciclos/'.$cycle->id.'/biometrias', [
            'sampled_on' => now()->toDateString(), 'sample_size' => 25, 'sample_weight_grams' => '3750', 'mortality' => 40,
        ])->assertSessionHasNoErrors();

        $metrics = $cycle->refresh()->metrics();
        $this->assertSame(960, $metrics->population());
        $this->assertEqualsWithDelta(150.0, $metrics->averageWeightGrams(), 1e-9);
        $this->assertEqualsWithDelta(144.0, $metrics->biomassKg(), 1e-9);
    }

    public function test_mortality_cannot_exceed_the_fish_in_the_pond(): void
    {
        $cycle = $this->openCycle();

        $this->actingAs($cycle->pond->user)->post('/ciclos/'.$cycle->id.'/biometrias', [
            'sampled_on' => now()->toDateString(), 'sample_size' => 10, 'sample_weight_grams' => 1000, 'mortality' => 1001,
        ])->assertSessionHasErrorsIn('sampling', 'mortality');
    }

    public function test_partial_harvests_keep_the_cycle_open(): void
    {
        $cycle = $this->openCycle();

        $this->actingAs($cycle->pond->user)->post('/ciclos/'.$cycle->id.'/cosechas', [
            'harvested_on' => now()->toDateString(), 'fish_count' => 400, 'kilograms' => '180,5',
            'destination' => 'Restaurante', 'ice_slaughter_kg' => 50, 'ice_cooling_kg' => '', 'ice_transport_kg' => 60,
        ])->assertSessionHas('status', 'Cosecha parcial registrada.');

        $cycle->refresh();
        $this->assertTrue($cycle->isOpen());
        $this->assertSame(600, $cycle->metrics()->population());
        $this->assertEqualsWithDelta(110.0, $cycle->harvests()->sole()->iceKg(), 1e-9);
    }

    public function test_harvesting_every_fish_closes_the_cycle(): void
    {
        $cycle = $this->openCycle();

        $this->actingAs($cycle->pond->user)->post('/ciclos/'.$cycle->id.'/cosechas', [
            'harvested_on' => now()->toDateString(), 'fish_count' => 1000, 'kilograms' => 450, 'destination' => 'Plaza',
        ])->assertSessionHas('status', 'Cosecha registrada y ciclo cerrado.');

        $this->assertSame(now()->toDateString(), $cycle->refresh()->closed_on?->toDateString());
    }

    public function test_final_harvest_flag_closes_the_cycle(): void
    {
        $cycle = $this->openCycle();

        $this->actingAs($cycle->pond->user)->post('/ciclos/'.$cycle->id.'/cosechas', [
            'harvested_on' => now()->toDateString(), 'fish_count' => 900, 'kilograms' => 400,
            'destination' => 'Plaza', 'close_cycle' => '1',
        ]);

        $this->assertFalse($cycle->refresh()->isOpen());
    }

    public function test_cannot_harvest_more_fish_than_remain(): void
    {
        $cycle = $this->openCycle();

        $this->actingAs($cycle->pond->user)->post('/ciclos/'.$cycle->id.'/cosechas', [
            'harvested_on' => now()->toDateString(), 'fish_count' => 1001, 'kilograms' => 450, 'destination' => 'Plaza',
        ])->assertSessionHasErrorsIn('harvest', ['fish_count' => 'Según la bitácora quedan 1000 peces en el estanque.']);
        $this->assertSame(0, $cycle->harvests()->count());
    }

    public function test_closed_cycles_are_read_only(): void
    {
        $cycle = Cycle::factory()->closed()->create();

        $this->actingAs($cycle->pond->user)->post('/ciclos/'.$cycle->id.'/tratamientos', [
            'applied_on' => now()->toDateString(), 'agent' => 'Sal', 'reason' => 'Prueba',
        ])->assertForbidden();
        $this->actingAs($cycle->pond->user)->get('/ciclos/'.$cycle->id)->assertOk()->assertSee('Cerrado');
    }

    public function test_closing_cannot_predate_the_last_entry(): void
    {
        $cycle = $this->openCycle();
        $cycle->treatments()->create(['applied_on' => now()->subDays(2)->toDateString(), 'agent' => 'Cal', 'reason' => 'pH']);

        $this->actingAs($cycle->pond->user)->post('/ciclos/'.$cycle->id.'/cierre', ['closed_on' => now()->subDays(3)->toDateString()])
            ->assertSessionHasErrorsIn('close', 'closed_on');
        $this->actingAs($cycle->pond->user)->post('/ciclos/'.$cycle->id.'/cierre', ['closed_on' => now()->subDays(2)->toDateString()])
            ->assertSessionHasNoErrors();
        $this->assertFalse($cycle->refresh()->isOpen());
    }

    public function test_owner_deletes_entries_of_an_open_cycle(): void
    {
        $cycle = $this->openCycle();
        $sampling = $cycle->samplings()->create(['sampled_on' => now()->toDateString(), 'sample_size' => 10, 'sample_weight_grams' => 500, 'mortality' => 3]);

        $this->actingAs($cycle->pond->user)->delete('/ciclos/'.$cycle->id.'/biometrias/'.$sampling->id)
            ->assertRedirect('/ciclos/'.$cycle->id.'#biometria');
        $this->assertModelMissing($sampling);
    }

    public function test_logbook_page_shows_the_indicators(): void
    {
        $cycle = $this->openCycle();
        $cycle->samplings()->create(['sampled_on' => now()->toDateString(), 'sample_size' => 20, 'sample_weight_grams' => 2400, 'mortality' => 10]);
        $cycle->feedings()->create(['feed_brand_id' => FeedBrand::factory()->create()->id, 'fed_on' => now()->toDateString(), 'kilograms' => 150, 'protein_percent' => 32]);

        $this->actingAs($cycle->pond->user)->get('/ciclos/'.$cycle->id)
            ->assertOk()
            ->assertSee('990')
            ->assertSee('120 g')
            ->assertSee('119 kg')
            ->assertSee('1,28');
    }
}
