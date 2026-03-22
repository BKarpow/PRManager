<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\BarcodeHandle;
use Illuminate\Support\Facades\Cache;

class DateProduct extends Model
{
    /** @use HasFactory<\Database\Factories\DateProductFactory> */
    use HasFactory;
    use BarcodeHandle;

    const KEY_CACHE = "dateProduct_exps";
    const KEY_CACHE2 = "dateProduct_exps2";
    const KEY_CACHE3 = "dateProduct_exps3";

    protected $fillable = [
        'group_id',
        'user_id',
        'product_id',
        'count',
        'start',
        'end',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'start' => 'date',
        'end' => 'date',
        // або 'datetime', якщо там є час
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }



    public function group()
    {
        return $this->belongsTo(User::class, 'id', 'group_id');
    }

    public function is25()
    {
        return $this->days_remaining <= $this->user->configDefaultDaysex();
    }

    public function getEndDate() {
        return date('d.m.y', strtotime($this->end));
    }

    public function barcodeSvgUrl()
    {
        return $this->saveBarcodeToFile($this->product->barcode);
    }
}
