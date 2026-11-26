<?php

namespace Tests\Feature;

use Tests\TestCase;

final class SecurityHeadersTest extends TestCase
{
    public function test_pages_ship_a_strict_content_security_policy(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $csp = (string) $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("script-src 'self'", $csp);
        $this->assertStringContainsString("frame-ancestors 'none'", $csp);
        $this->assertStringNotContainsString('unsafe-inline', $csp);
    }

    public function test_private_pages_are_not_cached(): void
    {
        $this->actingAs($this->farmer())->get('/panel')
            ->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_forms_carry_a_csrf_token(): void
    {
        $this->get('/ingresar')->assertSee('name="_token"', false);
    }

    public function test_health_check_responds(): void
    {
        $this->get('/up')->assertOk();
    }
}
