<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\NewDateProduct;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewDateProductMail;

class SendAdminEmainListener
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
        Mail::to(env('MAIL_ADMIN'))
            ->send(new NewDateProductMail($event->message));
    }
}
