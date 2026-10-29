<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200)
            ->assertSee('name="terms_accepted"', false)
            ->assertSee('name="privacy_acknowledged"', false)
            ->assertSee('value="1.0"', false)
            ->assertSee('El registro est&aacute; reservado a personas de 18 a&ntilde;os o m&aacute;s.', false);
    }

    public function test_provider_registration_screen_renders_the_same_legal_controls(): void
    {
        $this->get(route('provider.landing'))
            ->assertOk()
            ->assertSee('id="provider-terms-accepted"', false)
            ->assertSee('id="provider-privacy-acknowledged"', false)
            ->assertSee('value="1.0"', false);
    }

    public function test_new_users_can_register(): void
    {
        $level = UserLevel::firstOrCreate([
            'id' => User::LEVEL_SERVICE_PROVIDER,
        ], [
            'name' => 'Proveedor de servicio',
        ]);

        $this->get('/register')->assertOk();
        $registrationFormStartedAt = (int) session('registration_form_started_at');
        $this->travel(4)->seconds();

        $response = $this->withHeader('User-Agent', 'KConecta-Web-Test')->post('/register', [
            'user_level_id' => $level->id,
            'registration_form_started_at' => $registrationFormStartedAt,
            'website' => '',
            'document_type' => '',
            'document_number' => '',
            'first_name' => 'Test',
            'last_name' => '',
            'company_name' => '',
            'phone' => '600000000',
            'landline_phone' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms_accepted' => '1',
            'privacy_acknowledged' => '1',
            'legal_terms_version' => '1.0',
            'legal_privacy_version' => '1.0',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice', absolute: false));
        $this->assertDatabaseHas('user_address', [
            'user_id' => auth()->id(),
            'address' => null,
            'latitude' => null,
            'longitude' => null,
        ]);
        $this->assertDatabaseHas('legal_acceptances', [
            'user_id' => auth()->id(),
            'document_type' => 'terms',
            'document_version' => '1.0',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'KConecta-Web-Test',
        ]);
        $this->assertDatabaseHas('legal_acceptances', [
            'user_id' => auth()->id(),
            'document_type' => 'privacy',
            'document_version' => '1.0',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'KConecta-Web-Test',
        ]);
        $this->assertDatabaseCount('legal_acceptances', 2);
    }

    public function test_web_registration_requires_both_legal_confirmations(): void
    {
        $level = UserLevel::firstOrCreate([
            'id' => User::LEVEL_FINAL_CLIENT,
        ], [
            'name' => 'Cliente final',
        ]);

        $registrationFormStartedAt = $this->beginRegistration();
        $response = $this->post('/register', $this->validRegistrationData($level->id, $registrationFormStartedAt));

        $response->assertSessionHasErrors(['terms_accepted', 'privacy_acknowledged']);
        $this->assertGuest();
        $this->assertDatabaseCount('user', 0);
        $this->assertDatabaseCount('legal_acceptances', 0);
    }

    public function test_web_registration_rejects_stale_or_tampered_document_versions(): void
    {
        $level = UserLevel::firstOrCreate([
            'id' => User::LEVEL_FINAL_CLIENT,
        ], [
            'name' => 'Cliente final',
        ]);

        $registrationFormStartedAt = $this->beginRegistration();
        $response = $this->post('/register', $this->validRegistrationData($level->id, $registrationFormStartedAt) + [
            'terms_accepted' => '1',
            'privacy_acknowledged' => '1',
            'legal_terms_version' => '0.9',
            'legal_privacy_version' => '1.0',
        ]);

        $response->assertSessionHasErrors(['legal_terms_version']);
        $this->assertGuest();
        $this->assertDatabaseCount('user', 0);
        $this->assertDatabaseCount('legal_acceptances', 0);
    }

    private function beginRegistration(): int
    {
        $this->get('/register')->assertOk();
        $startedAt = (int) session('registration_form_started_at');
        $this->travel(4)->seconds();

        return $startedAt;
    }

    private function validRegistrationData(int $levelId, int $registrationFormStartedAt): array
    {
        return [
            'user_level_id' => $levelId,
            'registration_form_started_at' => $registrationFormStartedAt,
            'website' => '',
            'document_type' => '',
            'document_number' => '',
            'first_name' => 'Test',
            'last_name' => '',
            'company_name' => '',
            'phone' => '600000000',
            'landline_phone' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
    }
}
