<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    public function validateV3(string $token, float $minScore = 0.5): bool
    {
        $secret = env('RECAPTCHA_SECRET_KEY');
        if (! $secret || ! $token) {
            return false;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $token,
        ]);

        if (! $response->ok()) {
            return false;
        }

        $data = $response->json();

        return ! empty($data['success']) && ($data['score'] ?? 0) >= $minScore;
    }

    public function validateV2(string $token, ?string $ip = null): bool
    {
        $secret = env('RECAPTCHA_SECRET_KEY');
        if (! $secret || ! $token) {
            return false;
        }

        $payload = [
            'secret' => $secret,
            'response' => $token,
        ];

        if ($ip) {
            $payload['remoteip'] = $ip;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', $payload);

        if (! $response->ok()) {
            return false;
        }

        $data = $response->json();

        return ! empty($data['success']);
    }
}
