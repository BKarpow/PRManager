<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendExpire
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public User $user;
    public string $message;
    public bool $isExp;

    public function __construct(User $user, bool $uniq = true)
    {
        $this->user = $user;
        $exps = $user->expiredDays();
        $this->isExp = $exps->count() > 0;
        $this->message = "Перевірте ці продукти (продукт, скільки лишилось, кількість): \n";
        $i = 1;
        $ex = [];
        $uniqCount = 0;
        foreach($exps as $e) {
            $key = md5($e->product_id . $e->user_id .  $e->end);
            if (isset($ex[$key]) && $uniq) {
                $uniqCount++;
                continue;
            }
            $ex[$key] = true;
            $this->message .= (string)$i .". ". $e->product->name . ": " . $e->days_remaining . " день(ів) (".$e->count.")";
            $this->message .= "\n";
            $i++;
        }
        $ex = null;
        $this->message .= "Кількість копій: " . (string)$uniqCount . "\n";

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
