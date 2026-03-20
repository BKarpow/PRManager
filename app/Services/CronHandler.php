<?php

namespace App\Services;

use App\Services\ImageService;
use App\Services\ServiceHandler;
use App\Models\User;
use App\Jobs\SendExpsJob;

class CronHandler
{
    use ServiceHandler;

    public function __construct(public bool $oneDay = true)
    {

    }

    public function senderExps()
    {
        $users = User::orderBy('id', 'desc')->get();
        foreach($users as $u) {
            // event(new SendExpire($u));
            SendExpsJob::dispatch($u);

        }
    }

    public function execute()
    {
        $r = new ImageService();
        if (true) { // Раз на добу
            $this->senderExps();
            $r->runAutoDownload();
        } else {
            return "Stop running notify!";
        }
    }
}
