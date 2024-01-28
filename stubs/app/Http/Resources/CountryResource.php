<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'cca2' => $this->cca2,
            'cca3' => $this->cca3,
            'dialing_code' => $this->dialing_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
