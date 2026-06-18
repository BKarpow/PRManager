<?php
// app/DTOs/ExpiryDataDTO.php

namespace App\DTOs;

class ExpiryDataDTO
{
    public function __construct(
        public readonly ?string $expiryDate,
        public readonly ?string $productionDate,
        public readonly ?string $batchNumber,
        public readonly ?string $productName,
        public readonly float $confidenceScore,
        public readonly ?string $rawResponse = null,
        public readonly ?string $error = null
    ) {}

    public function toArray(): array
    {
        return [
            'expiry_date' => $this->expiryDate,
            'production_date' => $this->productionDate,
            'batch_number' => $this->batchNumber,
            'product_name' => $this->productName,
            'confidence_score' => $this->confidenceScore,
            'raw_response' => $this->rawResponse,
            'error' => $this->error,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function success(): bool
    {
        return $this->error === null && $this->expiryDate !== null;
    }
}
