<?php

namespace Tests\Feature;

use App\Models\ServiceType;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderLandingHeroTest extends TestCase
{
    use RefreshDatabase;

    public function test_provider_landing_renders_the_real_registration_interface(): void
    {
        ServiceType::query()->create(['name' => 'Fontaner?a']);

        $response = $this->get('/quiero-ser-proveedor');

        $response
            ->assertOk()
            ->assertSee('Brindo servicios')
            ->assertSee('Registro gratuito')
            ->assertSee('name="company_name"', false)
            ->assertSee('name="service_type_id"', false)
            ->assertSee('name="password"', false)
            ->assertSee('name="terms_accepted"', false)
            ->assertSee('img/kconecta/shop.webp')
            ->assertSee('img/kconecta/kconecta_logo.webp')
            ->assertDontSee('Formulario demostrativo', false);

        $this->assertStringContainsString('provider-registration.css', $response->getContent());
    }

    public function test_provider_landing_registers_a_provider_and_selected_service_type(): void
    {
        $serviceType = ServiceType::query()->create(['name' => 'Abogac?a']);
        $startedAt = now()->subSeconds(4)->timestamp;

        $response = $this->withSession(['provider_landing_registration_started_at' => $startedAt])
            ->post('/quiero-ser-proveedor', $this->registrationPayload($serviceType, $startedAt));

        $provider = User::query()->where('email', 'proveedor@test.dev')->firstOrFail();

        $response->assertRedirect(route('verification.notice'));
        $this->assertSame(User::LEVEL_SERVICE_PROVIDER, (int) $provider->user_level_id);
        $this->assertDatabaseHas('provider_services', [
            'provider_id' => $provider->id,
            'service_type_id' => $serviceType->id,
        ]);
        $this->assertTrue(UserAddress::query()->where('user_id', $provider->id)->exists());
        $this->assertAuthenticatedAs($provider);
    }

    public function test_provider_landing_rejects_duplicate_email_without_creating_a_second_provider(): void
    {
        $serviceType = ServiceType::query()->create(['name' => 'Electricidad']);
        User::query()->create([
            'first_name' => 'Proveedor existente',
            'user_name' => 'Proveedor existente',
            'email' => 'repetido@test.dev',
            'password' => 'hash',
            'user_level_id' => User::LEVEL_SERVICE_PROVIDER,
        ]);
        $startedAt = now()->subSeconds(4)->timestamp;
        $payload = $this->registrationPayload($serviceType, $startedAt);
        $payload['email'] = 'repetido@test.dev';

        $this->withSession(['provider_landing_registration_started_at' => $startedAt])
            ->from('/quiero-ser-proveedor')
            ->post('/quiero-ser-proveedor', $payload)
            ->assertRedirect('/quiero-ser-proveedor')
            ->assertSessionHasErrors('email');

        $this->assertSame(1, User::query()->where('email', 'repetido@test.dev')->count());
        $this->assertDatabaseMissing('provider_services', ['service_type_id' => $serviceType->id]);
    }

    public function test_provider_landing_requires_a_real_service_type_and_legal_acceptance(): void
    {
        $startedAt = now()->subSeconds(4)->timestamp;
        $payload = $this->registrationPayload(new ServiceType(['id' => 99999]), $startedAt);
        unset($payload['terms_accepted'], $payload['privacy_acknowledged']);

        $this->withSession(['provider_landing_registration_started_at' => $startedAt])
            ->from('/quiero-ser-proveedor')
            ->post('/quiero-ser-proveedor', $payload)
            ->assertRedirect('/quiero-ser-proveedor')
            ->assertSessionHasErrors(['service_type_id', 'terms_accepted', 'privacy_acknowledged']);

        $this->assertDatabaseMissing('user', ['email' => 'proveedor@test.dev']);
    }

    private function registrationPayload(ServiceType $serviceType, int $startedAt): array
    {
        return [
            'company_name' => 'Proveedor de prueba',
            'service_type_id' => $serviceType->id,
            'email' => 'proveedor@test.dev',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms_accepted' => '1',
            'privacy_acknowledged' => '1',
            'legal_terms_version' => (string) config('compliance.legal_acceptance.documents.terms'),
            'legal_privacy_version' => (string) config('compliance.legal_acceptance.documents.privacy'),
            'registration_form_started_at' => $startedAt,
        ];
    }
}
