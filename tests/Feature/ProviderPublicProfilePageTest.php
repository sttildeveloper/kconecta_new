<?php

namespace Tests\Feature;

use App\Models\ProviderService;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderPublicProfilePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_profile_normalizes_legacy_characters_for_display(): void
    {
        $provider = User::query()->create([
            'first_name' => '',
            'user_name' => 'Instalaciones Catalu?a Arag?n',
            'email' => 'perfil-publico@test.dev',
            'password' => 'hash',
            'user_level_id' => User::LEVEL_SERVICE_PROVIDER,
        ]);
        UserAddress::query()->create([
            'user_id' => $provider->id,
            'city' => 'Barcelona',
            'province' => 'Barcelona',
            'country' => 'Espa?a',
        ]);
        $serviceType = ServiceType::query()->create(['name' => 'Reformas integrales']);
        ProviderService::query()->create([
            'provider_id' => $provider->id,
            'service_type_id' => $serviceType->id,
        ]);

        $this->get('/result_provider/'.$provider->id)
            ->assertOk()
            ->assertSee('Instalaciones Cataluña Aragón')
            ->assertSee('Barcelona, Barcelona, España')
            ->assertDontSee('Catalu?a', false)
            ->assertDontSee('Arag?n', false)
            ->assertDontSee('Espa?a', false);
    }

    public function test_public_profile_uses_the_wider_provider_column_contract(): void
    {
        $css = file_get_contents(public_path('css/page/details.css'));

        $this->assertIsString($css);
        $this->assertStringContainsString('minmax(320px, 360px)', $css);
        $this->assertStringContainsString('background: #1476e8', $css);
    }
}
