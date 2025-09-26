<?php

namespace App\Http\Resources;

use App\Constants\TripType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
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
            'driver_id' => $this->driver_id,
            'type' => $this->type,
            //'type_name' => TripType::get_name($this->type),
            'status' => $this->status,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Include details using polymorphic relationship
            'details' => $this->when($this->detailable, function () {
                return $this->formatTripDetails();
            }),
            
            // Include clients
            'clients' => TripClientResource::collection($this->whenLoaded('clients')),
            
            // Include cargos (for cargo transport)
            'cargos' => TripCargoResource::collection($this->whenLoaded('cargos')),
            
            // Include driver information
            'driver' => new DriverResource($this->whenLoaded('driver')),
        ];
    }

    /**
     * Format trip details based on trip type
     */
    protected function formatTripDetails(): array
    {
        if (!$this->detailable) {
            return [];
        }

        $details = $this->detailable->toArray();
        
        // Add type-specific formatting
        switch ($this->type) {
            case TripType::TAXI_RIDE:
                return [
                    ...$details,
                    'starting_point' => $this->detailable->startingPoint,
                    'arrival_point' => $this->detailable->arrivalPoint,
                ];
                
            case TripType::CAR_RESCUE:
                return [
                    ...$details,
                    'breakdown_location' => $this->detailable->breakdownLocation,
                ];
                
            case TripType::CARGO_TRANSPORT:
                return [
                    ...$details,
                    'delivery_location' => $this->detailable->deliveryLocation,
                ];
                
            case TripType::WATER_TRANSPORT:
                return [
                    ...$details,
                    'delivery_location' => $this->detailable->deliveryLocation,
                ];
                
            case TripType::PAID_DRIVING:
                return [
                    ...$details,
                    'starting_location' => $this->detailable->startingLocation,
                    'arrival_location' => $this->detailable->arrivalLocation,
                ];
                
            case TripType::MRT_TRIP:
            case TripType::ESP_TRIP:
                return [
                    ...$details,
                    'starting_location' => $this->detailable->startingLocation,
                ];
                
            default:
                return $details;
        }
    }
}