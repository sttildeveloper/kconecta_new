<?php

namespace Tests\Feature;

use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    public function test_privacy_terms_and_account_deletion_pages_are_public(): void
    {
        $this->get('/legal/privacy')
            ->assertOk()
            ->assertSee('Politica de Privacidad')
            ->assertSee('Responsable del tratamiento')
            ->assertSee('Sttil Tecnology')
            ->assertSee('B19403427')
            ->assertSee('info@kconecta.com')
            ->assertSee('Agencia Española de Protección de Datos')
            ->assertSee('Conservacion de datos')
            ->assertSee('/legal/terms')
            ->assertSee('/legal/account-deletion');

        $this->get('/legal/terms')
            ->assertOk()
            ->assertSee('Términos y Condiciones')
            ->assertSee('Sttil Tecnology')
            ->assertSee('Uso permitido')
            ->assertSee('al menos 18 años')
            ->assertSee('Jurisdicción y ley aplicable')
            ->assertSee('/legal/privacy')
            ->assertSee('/legal/account-deletion');

        $this->get('/legal/account-deletion')
            ->assertOk()
            ->assertSee('Eliminación de Cuenta')
            ->assertSee('Sttil Tecnology')
            ->assertSee('Eliminación desde la app')
            ->assertSee('Datos retenidos y motivo')
            ->assertSee('/legal/privacy')
            ->assertSee('/legal/terms');

        foreach (['/legal/privacy', '/legal/terms', '/legal/account-deletion'] as $url) {
            $this->get($url)
                ->assertDontSee('José María Bardina Arancibia')
                ->assertDontSee('37661789W');
        }
    }

    public function test_legacy_privacy_route_redirects_permanently_to_canonical_url(): void
    {
        $this->get('/policy_and_privacy')
            ->assertStatus(301)
            ->assertRedirect('/legal/privacy');
    }
}
