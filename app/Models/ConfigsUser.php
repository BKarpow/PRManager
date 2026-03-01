<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigsUser extends Model
{
    const DEFAULT_SHOP_KEY = "shop";
    protected $fillable = [
        'user_id', // Обов'язково додаємо поле, яке викликало помилку
        'key',     // Це поле ви використовували для пошуку
        'value',   // Це поле ви оновлювали/створювали
        // Додайте тут усі інші поля, які ви хочете заповнювати масивом
    ];

}
