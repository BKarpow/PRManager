<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;
use App\Events\SendExpire;

class SendExpsJob implements ShouldQueue
{
    use Queueable;

    // Скільки разів намагатися виконати, якщо сталася помилка
    public $tries = 3;

    // Скільки секунд чекати перед повторною спробою
    public $backoff = 60;

    // Максимальний час виконання (щоб PHP не вбив скрипт раніше)
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        event(new SendExpire($this->user));
    }
}
