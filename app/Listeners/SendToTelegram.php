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
            $option = [
                [
                    $tg->buildInlineKeyBoardButton("🍏 Відкрити повний список", url('/')),
                    $tg->buildInlineKeyBoardButton("🆕 Додати новий термін", route('date.create'))
                ]
            ];

            $keyb = $tg->buildInlineKeyBoard($option);

            $tg->sendMessage([
                'chat_id' => $event->user->telegram->chat_id ?? '261711381',
                'text' => $event->message,
                'reply_markup' => $keyb,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true
            ]);
        }
    }
}
