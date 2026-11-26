<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Cycle;
use App\Models\FeedBrand;
use App\Models\Feeding;
use App\Models\Provider;
use App\Models\Species;
use App\Models\User;
use Tests\TestCase;

final class AdminCatalogTest extends TestCase
{
    public function test_admin_registers_farmers_who_can_then_sign_in(): void
    {
        $this->actingAs($this->admin())->post('/admin/piscicultores', [
            'name' => 'Rosa', 'last_name' => 'Pérez', 'email' => 'rosa@example.com', 'document' => '1023456789',
            'gender' => 'femenino', 'vereda' => 'El Carmen', 'active' => '1',
            'password' => 'ClaveInicial2026', 'password_confirmation' => 'ClaveInicial2026', 'role' => 'administrador',
        ])->assertSessionHasNoErrors();

        $rosa = User::query()->where('email', 'rosa@example.com')->sole();
        $this->assertSame(Role::Farmer, $rosa->role);
        $this->post('/salir');
        $this->post('/ingresar', ['email' => 'rosa@example.com', 'password' => 'ClaveInicial2026'])->assertRedirect('/panel');
    }

    public function test_editing_a_farmer_without_password_keeps_the_current_one(): void
    {
        $farmer = $this->farmer();
        $hash = $farmer->getAuthPassword();

        $this->actingAs($this->admin())->put('/admin/piscicultores/'.$farmer->id, [
            'name' => 'Nuevo', 'last_name' => $farmer->last_name, 'email' => $farmer->email, 'document' => $farmer->document,
            'password' => '', 'password_confirmation' => '',
        ])->assertSessionHasNoErrors();

        $farmer->refresh();
        $this->assertSame('Nuevo', $farmer->name);
        $this->assertSame($hash, $farmer->getAuthPassword());
        $this->assertFalse($farmer->active);
    }

    public function test_farmer_passwords_follow_the_policy(): void
    {
        $this->actingAs($this->admin())->post('/admin/piscicultores', [
            'name' => 'Rosa', 'last_name' => 'Pérez', 'email' => 'rosa@example.com', 'document' => '1023456789',
            'password' => 'corta', 'password_confirmation' => 'corta',
        ])->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'rosa@example.com']);
    }

    public function test_species_calendar_must_be_consistent(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/especies', [
            'name' => 'Cachama', 'fry_until_week' => 6, 'grow_out_until_week' => 5, 'harvest_week' => 4, 'market_weight_grams' => 600,
        ])->assertSessionHasErrors(['grow_out_until_week', 'harvest_week']);

        $this->actingAs($admin)->post('/admin/especies', [
            'name' => 'Cachama', 'fry_until_week' => 6, 'grow_out_until_week' => 16, 'harvest_week' => 28, 'market_weight_grams' => 600,
        ])->assertRedirect('/admin/especies');
        $this->assertDatabaseHas('species', ['name' => 'Cachama', 'harvest_week' => 28]);
    }

    public function test_species_in_use_cannot_be_deleted(): void
    {
        $cycle = Cycle::factory()->create();

        $this->actingAs($this->admin())->delete('/admin/especies/'.$cycle->species_id)->assertSessionHasErrors('delete');
        $this->assertModelExists($cycle->species);
    }

    public function test_provider_nit_is_validated_and_normalized(): void
    {
        $admin = $this->admin();
        $data = ['name' => 'Alevinos del Huila', 'city' => 'Neiva'];

        $this->actingAs($admin)->post('/admin/proveedores', [...$data, 'nit' => '800.197.268-5'])->assertSessionHasErrors('nit');
        $this->actingAs($admin)->post('/admin/proveedores', [...$data, 'nit' => '800.197.268'])->assertRedirect('/admin/proveedores');
        $this->assertSame('800197268-4', Provider::query()->sole()->nit);

        $this->actingAs($admin)->post('/admin/proveedores', [...$data, 'nit' => '800197268-4'])->assertSessionHasErrors('normalized_nit');
        $this->actingAs($admin)->get('/admin/proveedores')->assertSee('800.197.268-4');
    }

    public function test_feed_brands_with_feedings_cannot_be_deleted(): void
    {
        $feeding = Feeding::factory()->create();
        $unused = FeedBrand::factory()->create();
        $admin = $this->admin();

        $this->actingAs($admin)->delete('/admin/alimentos/'.$feeding->feed_brand_id)->assertSessionHasErrors('delete');
        $this->actingAs($admin)->delete('/admin/alimentos/'.$unused->id)->assertRedirect('/admin/alimentos');
        $this->assertModelMissing($unused);
    }

    public function test_farmer_search_matches_name_document_and_vereda(): void
    {
        User::factory()->create(['name' => 'Rosa', 'vereda' => 'El Carmen']);
        User::factory()->create(['name' => 'Pedro', 'vereda' => 'La Esperanza']);

        $this->actingAs($this->admin())->get('/admin/piscicultores?q=carmen')
            ->assertOk()->assertSee('Rosa')->assertDontSee('Pedro');
    }

    public function test_species_lists_render(): void
    {
        Species::factory()->create(['name' => 'Bocachico']);

        $this->actingAs($this->admin())->get('/admin/especies')->assertOk()->assertSee('Bocachico');
    }
}
