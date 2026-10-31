<?php

namespace Tests\Feature;

use App\Models\ProviderService;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
            ->assertSee('Otros servicios')
            ->assertSee('data-other-services-link', false)
            ->assertSee('img/kconecta/otros-servicios.svg')
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
        $this->assertStringContainsString('other_services', $javascript);
    }

    public function test_other_services_lists_providers_linked_outside_the_featured_catalog(): void
    {
        $featuredType = ServiceType::query()->create(['name' => 'Fontaner?a']);
        $otherType = ServiceType::query()->create(['name' => 'Abogac?a']);
        $featuredProvider = $this->makeProvider('FontanerÃ­a Destacada');
        $otherProvider = $this->makeProvider('Abogada Local');

        ProviderService::query()->create([
            'provider_id' => $featuredProvider->id,
            'service_type_id' => $featuredType->id,
        ]);
        ProviderService::query()->create([
            'provider_id' => $otherProvider->id,
            'service_type_id' => $otherType->id,
        ]);

        foreach ([$featuredProvider, $otherProvider] as $provider) {
            UserAddress::query()->create([
                'user_id' => $provider->id,
                'city' => 'Barcelona',
                'province' => 'Barcelona',
                'latitude' => '41.3900',
                'longitude' => '2.1600',
            ]);
        }

        $this->get('/result/services?mode=1&other_services=1&city=Barcelona')
            ->assertOk()
            ->assertSee('Resultados: Otros servicios')
            ->assertSee('Abogada Local')
            ->assertDontSee('FontanerÃ­a Destacada');
    }

    private function makeProvider(string $name): User
    {
        return User::query()->create([
            'first_name' => 'Test',
            'last_name' => 'Provider',
            'user_name' => $name,
            'email' => str($name)->slug().'-'.uniqid().'@test.dev',
            'password' => Hash::make('password'),
            'user_level_id' => User::LEVEL_SERVICE_PROVIDER,
            'email_verified_at' => now(),
        ]);
    }
}
