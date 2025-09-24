<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripCargoResource extends JsonResource
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
            'trip_id' => $this->trip_id,
            'cargo_id' => $this->cargo_id,
            'total_fees' => $this->total_fees,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Include cargo information
            'cargo' => new CargoResource($this->whenLoaded('cargo')),
        ];
    }
}