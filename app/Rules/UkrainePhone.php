<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UkrainePhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Регулярний вираз для форматів: 380671234567 або +380671234567
        // Перевіряє, щоб після 380 йшов один з кодів українських операторів (039, 050, 063, 066, 067, 068, 073, 089, 091-099)
        $pattern = '/^(\+?38)?(0(39|50|63|66|67|68|73|89|91|92|93|94|95|96|97|98|99)\d{7})$/';

        if (!preg_match($pattern, $value)) {
            $fail('Поле :attribute повинно бути коректним номером українського оператора.');
        }
    }
}
