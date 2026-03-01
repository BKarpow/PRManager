<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Ean13 implements Rule
{
    protected $errorType;

    public function passes($attribute, $value)
    {
        // Очистка від пробілів та дефісів
        $cleanValue = preg_replace('/[\s-]/', '', $value);

        // Перевірка на довжину
        if (strlen($cleanValue) !== 13) {
            $this->errorType = 'length';
            return false;
        }

        // Перевірка що всі символи - цифри
        if (!ctype_digit($cleanValue)) {
            $this->errorType = 'digits';
            return false;
        }

        // Перевірка контрольної цифри
        if (!$this->validateCheckDigit($cleanValue)) {
            $this->errorType = 'check_digit';
            return false;
        }

        return true;
    }

    protected function validateCheckDigit($ean)
    {
        $sum = 0;

        for ($i = 0; $i < 12; $i++) {
            $digit = (int)$ean[$i];
            // Непарні позиції (1, 3, 5, 7, 9, 11) множаться на 1
            // Парні позиції (2, 4, 6, 8, 10, 12) множаться на 3
            $multiplier = ($i % 2 === 0) ? 1 : 3;
            $sum += $digit * $multiplier;
        }

        $checkDigit = (10 - ($sum % 10)) % 10;

        return $checkDigit === (int)$ean[12];
    }

    public function message()
    {
        return match($this->errorType) {
            'length' => 'EAN13 код повинен містити рівно 13 цифр.',
            'digits' => 'EAN13 код повинен складатися лише з цифр.',
            'check_digit' => 'Невірна контрольна цифра в EAN13 коді.',
            default => 'Невірний формат EAN13 коду.',
        };
    }
}
