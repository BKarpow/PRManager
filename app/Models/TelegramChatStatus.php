<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramChatStatus extends Model
{
    protected $fillable = [

        'chat_id',     // Це поле ви використовували для пошуку
        'status',   // Це поле ви оновлювали/створювали
        'level'
    ];
}
