<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchForBarcodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int)$this->id,
            'idProduct' => (int)$this->product['id'],
            'barcode' => $this->product['barcode'],
            'name' => $this->product['name'],
            'end' => $this->end->format('d.m.Y'),
            'daysToExp' => $this->days
        ];
    }
}
