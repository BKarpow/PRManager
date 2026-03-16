<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\ImageService;

class AutoImageProductController extends Controller
{
    public function runAuto()
    {
        $r = new ImageService();
        $r->runAutoDownload();
    }
}
