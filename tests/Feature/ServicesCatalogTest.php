<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_services_route_renders_the_home_services_catalog_without_a_search(): void
    {
        $this->get('/result/services')
            ->assertOk()
            ->assertSee('Servicios para el hogar')
            ->assertSee('Busca el servicio que necesitas')
            ->assertSee('Explora los servicios')
            ->assertSee('Lampista')
            ->assertSee('Calefacción')
            ->assertSee('img/kconecta/service-types/fontaneria.webp')
            ->assertSee('img/kconecta/kconecta_logo.webp')
            ->assertDontSee('cat-lampista.jpg');
    }

    public function test_services_catalog_keeps_the_provider_results_route_for_a_selected_type(): void
    {
        $this->get('/result/services?sti%5B%5D=999&mode=1')
            ->assertOk()
            ->assertViewIs('page.result_all_service')
            ->assertSee('results-ui.css', false)
            ->assertSee('Filtros');
    }

    public function test_catalog_keeps_the_front_end_redirect_contract_for_a_legacy_service_url(): void
    {
        $this->get('/result/services?service=lampista')
            ->assertOk()
            ->assertViewIs('page.services-catalog')
            ->assertSee('data-service-key="lampista"', false)
            ->assertSee('data-service-id=', false)
            ->assertSee('demo-lampista-1.webp');

        $javascript = file_get_contents(public_path('js/services-catalog.js'));
        $this->assertIsString($javascript);
        $this->assertStringContainsString('reformas: 23', $javascript);
        $this->assertStringContainsString('resultUrlFor', $javascript);
    }
}
