<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Events\SendExpire;
use App\Jobs\SendExpsJob

class CronController extends Controller
{
    public function senderExps()
    {
        $users = User::orderBy('id', 'desc')->get();
        foreach($users as $u) {
            // event(new SendExpire($u));
            SendExpsJob::dispatch($u);

        }
    }
}
