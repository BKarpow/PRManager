<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ImageService;
use App\Services\BarcodeHandle;

class AutoImageProductController extends Controller
{
    use BarcodeHandle;
    public function runAuto()
    {
       $r = Product::pluck('barcode');
       foreach($r as $i) {
        $this->saveBarcodeToFile($i);
       }
    }
}
