<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceStorage extends Model
{
    protected $fillable = [
        'service', // Обов'язково додаємо поле, яке викликало помилку
        'key',     // Це поле ви використовували для пошуку
        'value',   // Це поле ви оновлювали/створювали
        // Додайте тут усі інші поля, які ви хочете заповнювати масивом
    ];
}
