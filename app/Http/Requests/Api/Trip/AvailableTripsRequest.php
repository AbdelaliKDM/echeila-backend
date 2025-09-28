<?php

namespace App\Http\Requests\Api\Trip;

use App\Constants\TripType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AvailableTripsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'trip_type' => ['required', 'string', Rule::in([TripType::MRT_TRIP, TripType::ESP_TRIP])],
            'starting_time' => 'required|date|after:now',
            'number_of_seats' => 'required|integer|min:1|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'trip_type.required' => 'Trip type is required.',
            'trip_type.in' => 'Trip type must be either MRT_TRIP or ESP_TRIP.',
            'starting_time.required' => 'Starting time is required.',
            'starting_time.date' => 'Starting time must be a valid date.',
            'starting_time.after' => 'Starting time must be in the future.',
            'number_of_seats.required' => 'Number of seats is required.',
            'number_of_seats.integer' => 'Number of seats must be an integer.',
            'number_of_seats.min' => 'Number of seats must be at least 1.',
            'number_of_seats.max' => 'Number of seats cannot exceed 50.',
        ];
    }
}