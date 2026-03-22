<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramVerificationService
{
    protected string $baseUrl = 'https://gatewayapi.telegram.org/';
    protected string $token;

    public function __construct()
    {
        // Отримуємо токен з конфігу
        $this->token = config('services.telegram.gateway_token');
    }

    public function sendCode(string $phoneNumber)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post($this->baseUrl . 'sendVerificationMessage', [
            'phone_number' => $phoneNumber,
            'code_length' => 6, // Довжина коду
            'ttl' => 300,       // Час життя 5 хв
        ]);

        return $response->json();
    }

    public function checkCode(string $requestId, string $code)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post($this->baseUrl . 'checkVerificationStatus', [
            'request_id' => $requestId,
            'code' => $code,
        ]);

        return $response->json();
    }
}
