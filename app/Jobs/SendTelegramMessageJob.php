<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Telegram;

class SendTelegramMessageJob implements ShouldQueue
{
    use Queueable;

    // Скільки разів намагатися виконати, якщо сталася помилка
    public $tries = 3;

    // Скільки секунд чекати перед повторною спробою
    public $backoff = 60;

    // Максимальний час виконання (щоб PHP не вбив скрипт раніше)
    public $timeout = 110;

    private Telegram $t;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $chatId, public string $text)
    {
        $this->t = new Telegram(env('TELEGRAM_BOT_TOKEN'));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $resp = $this->t->sendMessage([
            'chat_id' => $this->chatId,
            'text' => $this->text
        ]);
        // Log::channel('telegram')->debug($resp);
    }
}
