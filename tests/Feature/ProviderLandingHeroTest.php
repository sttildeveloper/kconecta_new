<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProviderLandingHeroTest extends TestCase
{
    public function test_provider_landing_renders_the_demo_onboarding_interface(): void
    {
        $response = $this->get('/quiero-ser-proveedor');

        $response
            ->assertOk()
            ->assertSee('Brindo servicios')
            ->assertSee('Cómo funciona la inscripción')
            ->assertSee('Registro gratuito')
            ->assertSee('Así completarías tu perfil después')
            ->assertSee('Formulario demostrativo, no conectado a ningún servicio.')
            ->assertSee('img/kconecta/shop.webp')
            ->assertSee('img/kconecta/kconecta_logo.webp')
            ->assertDontSee('name="user_level_id"', false)
            ->assertDontSee('name="password"', false)
            ->assertDontSee('img-hero-landing-quiero.webp');

        $this->assertStringContainsString('provider-onboarding.css', $response->getContent());
    }
}
