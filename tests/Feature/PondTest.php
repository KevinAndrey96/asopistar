<?php

namespace Tests\Feature;

use App\Models\Pond;
use Tests\TestCase;

final class PondTest extends TestCase
{
    public function test_farmers_register_ponds_with_decimal_commas(): void
    {
        $farmer = $this->farmer();

        $response = $this->actingAs($farmer)->post('/estanques', [
            'code' => 'E-01', 'area_m2' => '350,5', 'water_flow_lps' => '2,25', 'equipment' => 'Aireador',
        ]);

        $pond = Pond::query()->sole();
        $response->assertRedirect('/estanques/'.$pond->id);
        $this->assertSame($farmer->id, $pond->user_id);
        $this->assertSame('350.50', $pond->area_m2);
        $this->assertSame('2.25', $pond->water_flow_lps);
        $this->assertTrue($pond->active);
    }

    public function test_pond_codes_are_unique_per_farmer_only(): void
    {
        $farmer = $this->farmer();
        Pond::factory()->for($farmer)->create(['code' => 'E-01']);
        Pond::factory()->create(['code' => 'E-02']);
        $data = ['area_m2' => 100, 'water_flow_lps' => 1];

        $this->actingAs($farmer)->post('/estanques', ['code' => 'E-01', ...$data])
            ->assertSessionHasErrors(['code' => 'Ya tienes un estanque con ese código.']);
        $this->actingAs($farmer)->post('/estanques', ['code' => 'E-02', ...$data])->assertSessionHasNoErrors();
    }

    public function test_user_id_cannot_be_mass_assigned(): void
    {
        $farmer = $this->farmer();
        $victim = $this->farmer();

        $this->actingAs($farmer)->post('/estanques', [
            'code' => 'E-09', 'area_m2' => 100, 'water_flow_lps' => 1, 'user_id' => $victim->id,
        ]);

        $this->assertSame($farmer->id, Pond::query()->sole()->user_id);
    }

    public function test_ponds_with_an_open_cycle_cannot_be_deactivated(): void
    {
        $cycle = $this->openCycle();
        $pond = $cycle->pond;

        $this->actingAs($pond->user)->put('/estanques/'.$pond->id, [
            'code' => $pond->code, 'area_m2' => 100, 'water_flow_lps' => 1, 'active' => '0',
        ])->assertSessionHasErrors('active');
        $this->assertTrue($pond->refresh()->active);
    }

    public function test_dashboard_summarizes_open_cycles(): void
    {
        $cycle = $this->openCycle();

        $this->actingAs($cycle->pond->user)->get('/panel')
            ->assertOk()
            ->assertSee('Estanque '.$cycle->pond->code)
            ->assertSee('Levante')
            ->assertSee('1.000');
    }
}
