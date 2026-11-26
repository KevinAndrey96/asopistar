<?php

namespace Tests\Feature;

use App\Models\FeedBrand;
use App\Models\Feeding;
use Tests\TestCase;

final class AuthorizationTest extends TestCase
{
    public function test_farmers_cannot_open_ponds_or_cycles_of_other_farmers(): void
    {
        $cycle = $this->openCycle();
        $intruder = $this->farmer();

        $this->actingAs($intruder)->get('/estanques/'.$cycle->pond_id)->assertForbidden();
        $this->actingAs($intruder)->get('/estanques/'.$cycle->pond_id.'/editar')->assertForbidden();
        $this->actingAs($intruder)->get('/ciclos/'.$cycle->id)->assertForbidden();
        $this->actingAs($intruder)->get('/ciclos/'.$cycle->id.'/reporte')->assertForbidden();
    }

    public function test_farmers_cannot_write_in_the_logbook_of_other_farmers(): void
    {
        $cycle = $this->openCycle();
        $brand = FeedBrand::factory()->create();

        $this->actingAs($this->farmer())->post('/ciclos/'.$cycle->id.'/alimentaciones', [
            'fed_on' => now()->toDateString(), 'feed_brand_id' => $brand->id, 'kilograms' => 10,
        ])->assertForbidden();
        $this->assertDatabaseCount('feedings', 0);
    }

    public function test_farmers_cannot_reach_the_administration(): void
    {
        $farmer = $this->farmer();

        $this->actingAs($farmer)->get('/admin')->assertForbidden();
        $this->actingAs($farmer)->get('/admin/piscicultores')->assertForbidden();
        $this->actingAs($farmer)->post('/admin/especies', [])->assertForbidden();
    }

    public function test_administrators_read_logbooks_but_do_not_write_them(): void
    {
        $cycle = $this->openCycle();
        $admin = $this->admin();

        $this->actingAs($admin)->get('/ciclos/'.$cycle->id)->assertOk()->assertDontSee('Registrar cosecha');
        $this->actingAs($admin)->get('/estanques/'.$cycle->pond_id)->assertOk();
        $this->actingAs($admin)->post('/ciclos/'.$cycle->id.'/tratamientos', [
            'applied_on' => now()->toDateString(), 'agent' => 'Sal', 'reason' => 'Prueba',
        ])->assertForbidden();
        $this->actingAs($admin)->get('/panel')->assertForbidden();
    }

    public function test_entries_are_scoped_to_their_cycle(): void
    {
        $mine = $this->openCycle();
        $foreign = Feeding::factory()->create();

        $this->actingAs($mine->pond->user)
            ->delete('/ciclos/'.$mine->id.'/alimentaciones/'.$foreign->id)
            ->assertNotFound();
        $this->assertModelExists($foreign);
    }

    public function test_admin_farmer_routes_only_resolve_farmers(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/piscicultores/'.$admin->id)->assertNotFound();
    }
}
