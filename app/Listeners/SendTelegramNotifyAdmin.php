<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\NewDateProduct;
use Telegram;

// implements ShouldQueue

class SendTelegramNotifyAdmin
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
        $tg = new Telegram(env('TELEGRAM_BOT_TOKEN'));
        $res = $tg->sendMessage([
            'chat_id' => "261711381",
            'text' => $event->message
        ]);
    }
}
