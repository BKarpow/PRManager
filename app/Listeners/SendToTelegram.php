<?php

namespace App\Listeners;

use App\Events\SendExpire;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\InteractsWithQueue;
use Telegram;

class SendToTelegram
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
    public function handle(SendExpire $event): void
    {
        $tg = new Telegram(env('TELEGRAM_BOT_TOKEN'));

        if ($event->isExp) {

            $tg->sendMessage(['chat_id'=> $event->user->telegram->chat_id ?? '261711381','text'=>$event->message]);
        }
    }
}
