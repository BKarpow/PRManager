<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\DateProduct;

class NewDateProduct
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public DateProduct $date;
    public string $message;

    public function __construct(DateProduct $date)
    {
        $this->message = "Додано новий термін. \n";
        $this->message .= "Користувач: {$date->user->name}. \n";
        $this->message .= "Продкт: {$date->product->name}. \n";
        $this->message .= "Вжити до: {$date->end->format('d.m.Y')}. \n";
        $this->message .= "Штрихкод: {$date->product->barcode}. \n";
        
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
