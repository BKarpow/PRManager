<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramHandler extends Model
{
    /** @use HasFactory<\Database\Factories\TelegramHandlerFactory> */
    use HasFactory;

    const STATUS_READ_USER_ID = 0;
    const STATUS_AUTH_USER = 1;
    const STATUS_EXISTS_USER = 2;
    const STATUS_ERROR = 255;

    protected $fillable = [
       'user_id',
        'chat_id',     // Це поле ви використовували для пошуку

    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
