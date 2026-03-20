<?php

namespace App\Services;

use Picqer\Barcode\BarcodeGeneratorSVG;
use Illuminate\Support\Facades\Storage;

trait BarcodeHandle
{
    public function saveBarcodeToFile(string $barcodeValue)
    {
        if (strlen($barcodeValue) < 13) return ;
        $fileName = "barcodes/code_{$barcodeValue}.svg";
        if(Storage::disk('public')->exists($fileName)) return Storage::disk('public')->url($fileName);

        $generator = new BarcodeGeneratorSVG();

        // 1. Генеруємо SVG-код штрихкоду
        // Параметри: (значення, тип, ширина лінії, висота)
        $svgCode = $generator->getBarcode($barcodeValue, $generator::TYPE_EAN_13, 2, 60);

        // 2. Визначаємо шлях до файлу


        // 3. Зберігаємо файл на диск 'public'
        Storage::disk('public')->put($fileName, $svgCode);

        // Повертаємо URL для використання у Vue
        return Storage::disk('public')->url($fileName);
    }
}
