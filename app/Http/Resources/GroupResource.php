<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'name' => $this->name,
            'shop' => $this->shop->name,
            'comment' => $this->comment,
            'create' => $this->created_at->format('d.m.Y H:i:s'),
            'update' => $this->updated_at->format('d.m.Y H:i:s'),
        ];
    }
}
