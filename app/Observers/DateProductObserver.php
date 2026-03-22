<?php

namespace App\Observers;

use App\Models\DateProduct;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Events\NewDateProduct;

class DateProductObserver
{

    /**
     * Власний метод для очищення конкретного ключа
     */
    private function clearCache(): void
    {
        Cache::forget(DateProduct::KEY_CACHE.Auth::id());
        Cache::forget(DateProduct::KEY_CACHE2.Auth::id());
        Cache::forget(DateProduct::KEY_CACHE3.Auth::id());
        // Якщо використовуєш теги (для Redis):
        // Cache::tags(['products'])->flush();
    }

    /**
     * Handle the DateProduct "created" event.
     */
    public function created(DateProduct $dateProduct): void
    {
        $this->clearCache();
        event(new NewDateProduct($dateProduct));
    }

    /**
     * Handle the DateProduct "updated" event.
     */
    public function updated(DateProduct $dateProduct): void
    {
        $this->clearCache();
    }

    /**
     * Handle the DateProduct "deleted" event.
     */
    public function deleted(DateProduct $dateProduct): void
    {
        $this->clearCache();
    }

    /**
     * Handle the DateProduct "restored" event.
     */
    public function restored(DateProduct $dateProduct): void
    {
        $this->clearCache();
    }

    /**
     * Handle the DateProduct "force deleted" event.
     */
    public function forceDeleted(DateProduct $dateProduct): void
    {
        $this->clearCache();
    }
}
