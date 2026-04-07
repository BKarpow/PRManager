<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryWightProduct extends Model
{

    public function wproduct()
    {
        return $this->hasOne(WightProduct::class, 'id', 'wproduct_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
