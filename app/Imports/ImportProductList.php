<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Product;

class ImportProductList implements ToModel, WithStartRow
{

public function startRow(): int
    {
        return 2;
    }


    public function model(array $row)
    {
        // dd($row);
        $row[1] = (string)$row[1];
        if (empty($row[1])) return ;
        return Product::updateOrCreate([
            'barcode' => $row[1]
        ], [
            'name' => $row[0],
            'barcode' => $row[1]
        ]);
    }
}
