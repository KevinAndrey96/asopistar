<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

final class AuthenticationTest extends TestCase
{
    public function test_farmers_land_on_their_dashboard(): void
    {
        $farmer = $this->farmer();

        $this->post('/ingresar', ['email' => $farmer->email, 'password' => 'clave-de-prueba-1'])
            ->assertRedirect('/panel');
        $this->assertAuthenticatedAs($farmer);
    }

    public function test_administrators_land_on_the_association_summary(): void
    {
        $admin = $this->admin();

        $this->post('/ingresar', ['email' => $admin->email, 'password' => 'clave-de-prueba-1'])
            ->assertRedirect('/admin');
    }

    public function test_wrong_password_is_rejected_with_a_generic_message(): void
    {
        $farmer = $this->farmer();

        $this->from('/ingresar')->post('/ingresar', ['email' => $farmer->email, 'password' => 'otra'])
            ->assertRedirect('/ingresar')
            ->assertSessionHasErrors(['email' => 'El correo o la contraseña no son correctos.']);
        $this->assertGuest();
    }

    public function test_inactive_accounts_cannot_sign_in(): void
    {
        $farmer = User::factory()->inactive()->create();

        $this->post('/ingresar', ['email' => $farmer->email, 'password' => 'clave-de-prueba-1'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_accounts_deactivated_mid_session_are_signed_out(): void
    {
        $farmer = $this->farmer();
        $this->actingAs($farmer)->get('/panel')->assertOk();

        $farmer->update(['active' => false]);

        $this->get('/panel')->assertRedirect('/ingresar');
        $this->assertGuest();
    }

    public function test_repeated_failures_are_throttled(): void
    {
        $farmer = $this->farmer();
        foreach (range(1, 5) as $attempt) {
            $this->post('/ingresar', ['email' => $farmer->email, 'password' => 'mala-'.$attempt]);
        }

        $this->post('/ingresar', ['email' => $farmer->email, 'password' => 'clave-de-prueba-1'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $this->get('/panel')->assertRedirect('/ingresar');
        $this->get('/admin')->assertRedirect('/ingresar');
    }

    public function test_there_is_no_public_registration(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register')->assertNotFound();
    }

    public function test_signing_out_invalidates_the_session(): void
    {
        $this->actingAs($this->farmer())->post('/salir')->assertRedirect('/ingresar');
        $this->assertGuest();
    }

    public function test_users_change_their_password_with_the_current_one(): void
    {
        $farmer = $this->farmer();

        $this->actingAs($farmer)->put('/cuenta/clave', [
            'current_password' => 'incorrecta',
            'password' => 'NuevaClave2026',
            'password_confirmation' => 'NuevaClave2026',
        ])->assertSessionHasErrors('current_password');

        $this->actingAs($farmer)->put('/cuenta/clave', [
            'current_password' => 'clave-de-prueba-1',
            'password' => 'NuevaClave2026',
            'password_confirmation' => 'NuevaClave2026',
        ])->assertRedirect('/panel');

        $this->post('/salir');
        $this->post('/ingresar', ['email' => $farmer->email, 'password' => 'NuevaClave2026'])->assertRedirect('/panel');
    }
}
