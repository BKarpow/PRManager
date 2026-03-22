<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\NewDateProduct;
use Telegram;
use App\Jobs\SendTelegramMessageJob;

// implements ShouldQueue

class SendTelegramNotifyAdmin implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewDateProduct $event): void
    {
        // SendTelegramMessageJob::dispatch("261711381", $event->message);
        $tg = new Telegram(env('TELEGRAM_BOT_TOKEN'));
        $res = $tg->sendMessage([
            'chat_id' => "261711381",
            'text' => $event->message
        ]);
    }
}
