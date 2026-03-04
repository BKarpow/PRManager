<?php

namespace App\Http\Controllers;

use App\Events\SendExpire;
use App\Models\TelegramHandler;
use App\Http\Requests\StoreTelegramHandlerRequest;
use App\Http\Requests\UpdateTelegramHandlerRequest;
use Illuminate\Support\Facades\Log;
use App\Models\TelegramChatStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TelegramHandlerController extends Controller
{

    private $chatID = "";
    private \Telegram $tg;

    public function __construct()
    {
        $this->tg = new \Telegram(env('TELEGRAM_BOT_TOKEN'));
    }

    private function sendText(string $t)
    {
        $content = array('chat_id' => $this->chatID, 'text' => $t);
        Log::channel('telegram')->debug('Дані в телеграм:', $content);
        $this->tg->sendMessage($content);
    }

    public function sendContactRequest($chatId)
    {

        // Створюємо кнопку для запиту контакту
        $button = [
            'text' => '📱 Поділитися номером телефону',
            'request_contact' => true
        ];

        // Формуємо клавіатуру (масив масивів, бо це рядки кнопок)
        $keyboard = [
            [$button]
        ];

        $replyMarkup = json_encode([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $content = [
            'chat_id' => $chatId,
            'reply_markup' => $replyMarkup,
            'text' => "Будь ласка, натисніть кнопку нижче, щоб надіслати свій номер телефону для авторищації на сайті."
        ];

        return $this->tg->sendMessage($content);
    }

    private function cleanerEmpty(string $chat_id) {
        $r = TelegramChatStatus::where('chat_id', $chat_id)->first();
            if ($r) {
                $r->delete();
            }
    }

    private function getStatusChatId(string $chat_id)
    {
        $s = TelegramChatStatus::where('chat_id', $chat_id)->first();
        if (!$s) {
            $s = new TelegramChatStatus();
            $s->chat_id = $chat_id;
            $s->save();
        }
        return $s;
    }

    private function setStatusChatId(string $chat_id, int $status)
    {
        if ($status === TelegramHandler::STATUS_ERROR) {
            $this->cleanerEmpty($chat_id);
        }
        TelegramChatStatus::updateOrCreate([
            'chat_id' => $chat_id
        ], [
            'status' => $status
        ]);
    }

    private function conUserAndChatId(string $chat_id, int $user_id)
    {
        $s = TelegramHandler::where('chat_id', $chat_id)->first();
        if (TelegramHandler::where('user_id', $user_id)->first()) {
            $this->setStatusChatId($chat_id, TelegramHandler::STATUS_ERROR);
            return;
        }
        if ($s) {
            $this->setStatusChatId($chat_id, TelegramHandler::STATUS_ERROR);
            return;
        }
        $u = User::find($user_id);
        if (!$u) {
            $this->sendText("Користувача на сайті з цим номером не знайдено!");
            return;
        }
        $t = new TelegramHandler();
        $t->chat_id =  $chat_id;
        $t->user_id = $u->id;
        $t->save();
        $this->setStatusChatId($chat_id, TelegramHandler::STATUS_EXISTS_USER);
    }

    private function conUserPhoneAndChatId()
    {

        $result = $this->tg->getData();
        $u = null;
        if (isset($result['message']['contact'])) {
            $phoneNumber = $result['message']['contact']['phone_number'];
            $userId = $result['message']['contact']['user_id'];
            $u = User::wherePhone($phoneNumber)->first();
            Log::channel('telegram')->info('Телефон користувача '.$phoneNumber.', userId: '.$userId);
        } else {
            Log::channel('telegram')->error('Помилка отримання номеру телефону');
        }
        $s = TelegramHandler::where('chat_id', $this->chatID)->first();
        if (!$u || TelegramHandler::where('user_id', $u->id)->first()) {
            $this->sendText("Користувача на сайті з цим номером не знайдено!");
            return;
        }
        if ($s) {
            $this->setStatusChatId($this->chatID, TelegramHandler::STATUS_ERROR);
            return;
        }
        $t = new TelegramHandler();
        $t->chat_id =  $this->chatID;
        $t->user_id = $u->id;
        $t->save();
        $this->setStatusChatId($this->chatID, TelegramHandler::STATUS_EXISTS_USER);
    }

    private function commUserInfo()
    {
        $tu = TelegramHandler::where('chat_id', $this->chatID)->first();
        if ($tu) {
            $text = "Ваші дані на сайті.\n";
            $text .= "ID: {$tu->user->id}.\n";
            $text .= "Ім'я: {$tu->user->name}.\n";
            $text .= "Email: {$tu->user->email}.\n";
            $text .= "Телефон: {$tu->user->phone}.\n";
        } else {
            $text = "Помилка отримання даних на сайті.\n";
            $this->cleanerEmpty($this->chatID);
        }
        $this->sendText($text);
    }

    private function commSendExps()
    {
        $tu = TelegramHandler::where('chat_id', $this->chatID)->first();
        if ($tu) {
            event(new SendExpire($tu->user));
        } else {
            $text = "Помилка отримання даних на сайті.\n";
            $this->sendText($text);
        }
    }

    private function commandHandler()
    {
        switch ($this->tg->Text()) {
            case "/start":
                $this->commUserInfo();
                break;
            case "/exps":
                $this->commSendExps();
                break;
            default:
                $this->sendText("Команда не коректна!");
                break;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::channel('telegram')->info('Запуск webhook');

        $chat_id = $this->tg->ChatID();
        $this->chatID = $chat_id;
        $status = $this->getStatusChatId($chat_id);
        $text = "";
        switch ($status->status) {
            case TelegramHandler::STATUS_READ_USER_ID:
                $this->sendContactRequest($this->chatID);
                $this->setStatusChatId($chat_id, TelegramHandler::STATUS_AUTH_USER);

                break;
            case TelegramHandler::STATUS_AUTH_USER:
                $text = "Обробка, натисніть /start";
                $m = $this->tg->Text();
                $this->conUserPhoneAndChatId();
                $this->setStatusChatId($chat_id, TelegramHandler::STATUS_EXISTS_USER);
                $this->sendText($text);
                break;
            case TelegramHandler::STATUS_EXISTS_USER:
                $this->commandHandler();
                break;
            case TelegramHandler::STATUS_ERROR:
                $text = "Сталася помилка, можливо вас немає на сайті? Спробуйте ще створити акаунт на номер телефону який прив'язано до цього Telegram";
                $this->setStatusChatId($chat_id, TelegramHandler::STATUS_READ_USER_ID);
                $this->sendText($text);
                break;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function test()
    {
        dd(Auth::user()->telegram->chat_id);
    }
}
