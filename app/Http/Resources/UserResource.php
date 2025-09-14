<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
      'phone' => $this->phone,
      'status' => $this->status,
      'balance' => $this->balance,
      'passenger' => new PassengerResource($this->whenLoaded('passenger')),
      'federation' => new FederationResource($this->whenLoaded('federation')),
      'driver' => new DriverResource($this->whenLoaded('driver')),
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
  }
}
