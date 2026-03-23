<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\DateProduct;

trait CacheHandleTrait
{

    private function clearAllCacheUser($i)
    {
        Cache::forget(DateProduct::KEY_CACHE . $i);
        Cache::forget(DateProduct::KEY_CACHE2 . $i);
        Cache::forget(DateProduct::KEY_CACHE3 . $i);
        // Якщо використовуєш теги (для Redis):
        // Cache::tags(['products'])->flush();
    }
}
