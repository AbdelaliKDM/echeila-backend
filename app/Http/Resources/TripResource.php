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
            
            // Include details using polymorphic relationship with dedicated resources
            'details' => $this->when($this->detailable, function () {
                return $this->formatTripDetails();
            }),

            // Include driver information
            'driver' => new DriverResource($this->whenLoaded('driver')),

            // Include passenger for all trip types except international trips
            'client' => $this->when(
                !in_array($this->type, [TripType::MRT_TRIP, TripType::ESP_TRIP]) && $this->relationLoaded('client'),
                new TripClientResource($this->client)
            ),
            
            // Include cargos only for cargo transport trips
            'cargo' => $this->when(
                $this->type === TripType::CARGO_TRANSPORT,
                TripCargoResource::collection($this->whenLoaded('cargo'))
            ),
        ];
    }

    /**
     * Format trip details using dedicated resources based on trip type
     */
    protected function formatTripDetails()
    {
        if (!$this->detailable) {
            return null;
        }

        return match ($this->type) {
            TripType::TAXI_RIDE => new TaxiRideDetailResource($this->detailable),
            TripType::CAR_RESCUE => new CarRescueDetailResource($this->detailable),
            TripType::CARGO_TRANSPORT => new CargoTransportDetailResource($this->detailable),
            TripType::WATER_TRANSPORT => new WaterTransportDetailResource($this->detailable),
            TripType::PAID_DRIVING => new PaidDrivingDetailResource($this->detailable),
            TripType::MRT_TRIP, TripType::ESP_TRIP => new InternationalTripDetailResource($this->detailable),
            default => $this->detailable->toArray(),
        };
    }
}