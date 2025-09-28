<?php

namespace App\Http\Requests\Api\Trip;

use App\Constants\TripType;
use App\Constants\TripStatus;
use App\Constants\RideType;
use App\Constants\WaterType;
use App\Constants\Direction;
use App\Constants\VehicleType;
use App\Constants\MalfunctionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $baseRules = [
            'status' => ['required', 'string', Rule::in(TripStatus::all())],
            'note' => 'nullable|string|max:1000',
        ];

        // Get trip from route model binding to determine type
        $trip = $this->route('trip');
        
        // Add trip-specific rules for international trips only if before starting time
        if ($trip && in_array($trip->type, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
            return array_merge($baseRules, $this->getInternationalTripUpdateRules());
        }

        return $baseRules;
    }

        public function validateResolved()
    {
    }

    protected function getInternationalTripUpdateRules(): array
    {
        return [
            'direction' => ['sometimes', 'string', Rule::in(Direction::all())],
            'starting_place' => 'sometimes|string|max:255',
            'starting_time' => 'sometimes|date|after:now',
            'arrival_time' => 'sometimes|date|after:starting_time',
            'total_seats' => 'sometimes|integer|min:1|max:50',
            'seat_price' => 'sometimes|numeric|min:0',
        ];
    }
}