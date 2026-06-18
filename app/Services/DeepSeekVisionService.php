<?php
// app/Services/DeepSeekVisionService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use App\DTOs\ExpiryDataDTO;
use Illuminate\Support\Facades\Log;

class DeepSeekVisionService
{
    protected string $apiKey;
    protected string $apiUrl;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = env('DEEPSEEK_API_KEY');
        // Використовуємо спеціальний ендпоінт для мультимодальних запитів
        $this->apiUrl = env('DEEPSEEK_API_URL', 'https://api.deepseek.com/v1/chat/completions');
        // ВАЖЛИВО: потрібна мультимодальна модель
        $this->model = env('DEEPSEEK_MODEL', 'deepseek-mm-1.5'); // або 'deepseek-mm-1.5'
    }

    /**
     * Прямий аналіз зображення через DeepSeek (без сторонніх OCR)
     */
    public function extractExpiryDate(UploadedFile $image): ExpiryDataDTO
    {
        try {
            // Конвертуємо зображення в base64
            $imageContent = file_get_contents($image->getRealPath());
            $imageBase64 = base64_encode($imageContent);
            $mimeType = $image->getMimeType();

            // Перевірка формату
            if (!in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png'])) {
                return new ExpiryDataDTO(
                    expiryDate: null,
                    productionDate: null,
                    batchNumber: null,
                    productName: null,
                    confidenceScore: 0.0,
                    error: 'Підтримуються лише формати JPEG та PNG'
                );
            }

            // Формуємо data URL для зображення
            $dataUrl = "data:{$mimeType};base64,{$imageBase64}";

            // Викликаємо DeepSeek API
            $response = $this->callDeepSeekVisionAPI($dataUrl);

            if (!$response->successful()) {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? $response->body();

                // Перевіряємо чи проблема в моделі
                if (str_contains($errorMessage, 'does not support image')) {
                    return new ExpiryDataDTO(
                        expiryDate: null,
                        productionDate: null,
                        batchNumber: null,
                        productName: null,
                        confidenceScore: 0.0,
                        error: 'Ваш API ключ не має доступу до мультимодальної моделі. Використовуйте deepseek-vl2'
                    );
                }

                return new ExpiryDataDTO(
                    expiryDate: null,
                    productionDate: null,
                    batchNumber: null,
                    productName: null,
                    confidenceScore: 0.0,
                    error: "API помилка: {$errorMessage}"
                );
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? '';

            return $this->parseAIResponse($content);

        } catch (\Exception $e) {
            Log::error('DeepSeek Vision API error: ' . $e->getMessage());
            return new ExpiryDataDTO(
                expiryDate: null,
                productionDate: null,
                batchNumber: null,
                productName: null,
                confidenceScore: 0.0,
                error: $e->getMessage()
            );
        }
    }

    /**
     * Виклик мультимодального API DeepSeek
     */
    protected function callDeepSeekVisionAPI(string $imageDataUrl)
    {
        // Спеціальний формат для VL моделей з токеном <|ref|> [citation:1]
        $prompt = $this->buildVisionPrompt();

        $payload = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $imageDataUrl
                            ]
                        ],
                        [
                            'type' => 'text',
                            'text' => $prompt
                        ]
                    ]
                ]
            ],
            'temperature' => 0.1,
            'max_tokens' => 500,
            'response_format' => ['type' => 'json_object']
        ];

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post($this->apiUrl, $payload);
    }

    /**
     * Промпт для Vision моделі
     */
    protected function buildVisionPrompt(): string
    {
        return <<<PROMPT
Проаналізуй це зображення упаковки продукту. Знайди та поверни у форматі JSON:

{
    "expiry_date": "термін придатності у форматі YYYY-MM-DD",
    "production_date": "дата виробництва у форматі YYYY-MM-DD",
    "batch_number": "номер партії",
    "product_name": "назва продукту",
    "confidence_score": 0.95
}

Якщо якесь поле відсутнє, постав null.
Тільки JSON, без пояснень.
PROMPT;
    }

    /**
     * Парсинг відповіді
     */
    protected function parseAIResponse(string $content): ExpiryDataDTO
    {
        $content = preg_replace('/```json\s*|\s*```/', '', $content);

        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $content = $matches[0];
        }

        try {
            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            return new ExpiryDataDTO(
                expiryDate: $data['expiry_date'] ?? null,
                productionDate: $data['production_date'] ?? null,
                batchNumber: $data['batch_number'] ?? null,
                productName: $data['product_name'] ?? null,
                confidenceScore: (float)($data['confidence_score'] ?? 0.0),
                rawResponse: $content
            );
        } catch (\JsonException $e) {
            return new ExpiryDataDTO(
                expiryDate: null,
                productionDate: null,
                batchNumber: null,
                productName: null,
                confidenceScore: 0.0,
                rawResponse: $content,
                error: 'Не вдалося розпізнати дані'
            );
        }
    }
}
