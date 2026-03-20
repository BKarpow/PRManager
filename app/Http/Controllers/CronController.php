<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Events\SendExpire;
use App\Jobs\SendExpsJob;
use App\Services\ServiceHandler;
use App\Services\CronHandler;

class CronController extends Controller
{
    use ServiceHandler ;

    public function testNewDay()
    {
        dd($this->isNewDay());
    }


    public function run(Request $request)
    {
        // $r = new ImageService();
        $cron = new CronHandler();
        // $cron->execute();
        // if ($this->isNewDay()) { // Раз на добу
        //     $this->senderExps();
        //     $r->runAutoDownload();
        // } else {
        //     return "Stop running notify!";
        // }
    }
}
