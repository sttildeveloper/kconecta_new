<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_renders_the_new_kconecta_entry_experience(): void
    {
        $now = now();
        DB::table('service_type')->insert([
            ['id' => 1, 'name' => 'Fontanería', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Electricidad', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Enfermería', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Alba?iler?a', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Encuentra el servicio que')
            ->assertSee('Usar mi ubicación')
            ->assertSee('Escribir dirección en este lugar')
            ->assertSee('Servicios para el hogar')
            ->assertSee('Ofrezco servicios')
            ->assertSee('Eres un profesional o Manitas')
            ->assertSee('Inscríbete gratuitamente y gana visibilidad')
            ->assertSee('Inmobiliaria')
            ->assertSee('img/kconecta/kconecta_logo.webp')
            ->assertSee('img/kconecta/map.webp')
            ->assertSee('Fontanería')
            ->assertSee('Albañilería')
            ->assertDontSee('Alba?iler?a')
            ->assertSee('Electricidad')
            ->assertDontSee('Enfermería');
    }

    public function test_home_keeps_real_navigation_destinations(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('/result/services', false)
            ->assertSee('/quiero-ser-proveedor', false)
            ->assertSee('https://www.damelodamelo.com', false)
            ->assertSee('data-home-latitude', false)
            ->assertSee('data-home-longitude', false);
    }

    public function test_home_has_the_new_responsive_navigation_contract(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('class="kc-header"', false)
            ->assertSee('id="kc-navigation"', false)
            ->assertSee('Regístrate gratis');

        $css = file_get_contents(public_path('css/page/home.css'));
        $this->assertIsString($css);
        $this->assertStringContainsString('--kc-sky:#8bd6fa', $css);
        $this->assertStringContainsString('@media(max-width:680px)', $css);
    }

    public function test_geolocation_is_requested_only_after_the_explicit_action(): void
    {
        $javascript = file_get_contents(public_path('js/home.js'));
        $this->assertIsString($javascript);
        $this->assertStringContainsString("locationButton.addEventListener('click'", $javascript);
        $this->assertStringContainsString('navigator.geolocation.getCurrentPosition(', $javascript);
        $this->assertStringContainsString("locationForm.querySelector('[data-home-zoom]')", $javascript);
        $this->assertStringContainsString('sessionStorage.setItem(storageKey', $javascript);
        $this->assertStringContainsString('Ubicación aplicada. Elige el servicio que necesitas.', $javascript);
        $this->assertStringContainsString('openCatalog(locationForm.dataset.homeCatalogUrl)', $javascript);
        $this->assertSame(1, substr_count($javascript, 'navigator.geolocation.getCurrentPosition('));
    }
}
