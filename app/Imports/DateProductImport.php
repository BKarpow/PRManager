<?php

namespace App\Imports;

use App\Models\DateProduct;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DateProductImport implements ToModel, WithStartRow
{
    protected function revDate(string $d):string
    {
        return date('Y-m-d', strtotime($d." 00:00:00"));
    }

    /**
     * Вказуємо, з якого рядка починати читання.
     * Рядок 1 — це зазвичай заголовки, тому почнемо з 2.
     */
    public function startRow(): int
    {
        return 2;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        if (empty($row[5])) return null;


        return DateProduct::withoutEvents(function () {
            $p = Product::whereBarcode($row[5])->first();
        if (!$p) {
            $p = new Product();
            $p->name = $row[0];
            $p->barcode = $row[5];
            $p->save();
        }
            $dg = Auth::user()->configDefaultGroup();
            return DateProduct::updateOrCreate([
            'group_id' => $dg,
            'product_id' => (int)$p->id,
            'start' => $this->revDate($row[1]),
            'end' => $this->revDate($row[2])
        ],[
            'user_id' => Auth::id(),
            'group_id' => $dg,
            'product_id' => (int)$p->id,
            'start' => $this->revDate($row[1]),
            'end' => $this->revDate($row[2]),
            'count' => (int)$row[3],
        ]);
        });

    }
}
