<?php

namespace App\Http\Resources;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'userId' => (int)$this->id,
            'user' => $this->name,
            'email' => $this->email,
            'defaultShop' => (int)$this->configDefaultShop(),
            'defaultGroup' => (int)$this->configDefaultGroup(),
        ];
    }
}
