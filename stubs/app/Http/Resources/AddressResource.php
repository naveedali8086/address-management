<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'line_1' => $this->line_1,
            'line_2' => $this->line_2,
            'postal_code' => $this->postal_code,
            'country' => CountryResource::collection($this->whenLoaded('country')),
            'region' => RegionResource::collection($this->whenLoaded('region')),
            'city' => CityResource::collection($this->whenLoaded('city')),
            'landmark' => $this->landmark,
            'lat' => $this->lat,
            'long' => $this->long,
            'belongs_to' => $this->belongs_to,
            'belongs_to_id' => $this->addressable_id,
            'applicable_for_shipping' => $this->applicable_for_shipping,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
