<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripClientResource extends JsonResource
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
            'client_id' => $this->client_id,
            'client_type' => $this->client_type,
            'number_of_seats' => $this->number_of_seats,
            'total_fees' => $this->total_fees,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Include client information
            'client' => $this->whenLoaded('client', function () {
                return $this->client_type === 'App\Models\Passenger' 
                    ? new PassengerResource($this->client)
                    : new GuestResource($this->client);
            }),
        ];
    }
}