<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryWProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => $this->wproduct->name,
            'value' => (float)$this->value,
            'user' => $this->user->name,
            'created' => $this->created_at->format('d.m.Y H:i'),
            'updated' => $this->updated_at->format('d.m.Y H:i'),
        ];
    }
}
