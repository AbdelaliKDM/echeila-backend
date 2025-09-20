<?php

namespace App\Http\Requests\Api\Driver;

use App\Constants\CardType;
use App\Constants\TripType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Driver fields
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'email' => 'required|email',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Vehicle fields
            'vehicle.model_id' => 'required|exists:models,id',
            'vehicle.color_id' => 'required|exists:colors,id',
            'vehicle.production_year' => 'required|integer|min:1900|max:' . date('Y'),
            'vehicle.plate_number' => 'required|string|max:255|unique:vehicles,plate_number',
            'vehicle.image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'vehicle.permit' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            
            // Services (array of trip types)
            'services' => 'required|array|min:1',
            'services.*' => ['required', 'string', Rule::in(TripType::all())],
            
            // Cards
            'cards.national_id.expiration_date' => 'required|date|after:today',
            'cards.national_id.front_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cards.national_id.back_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            
            'cards.driving_license.expiration_date' => 'required|date|after:today',
            'cards.driving_license.front_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cards.driving_license.back_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'federation_id.required' => 'Federation is required.',
            'federation_id.exists' => 'Selected federation does not exist.',
            'vehicle.model_id.required' => 'Vehicle model is required.',
            'vehicle.model_id.exists' => 'Selected vehicle model does not exist.',
            'vehicle.color_id.required' => 'Vehicle color is required.',
            'vehicle.color_id.exists' => 'Selected vehicle color does not exist.',
            'vehicle.plate_number.unique' => 'This plate number is already registered.',
            'services.required' => 'At least one service must be selected.',
            'services.*.in' => 'Invalid service type selected.',
            'cards.national_id.expiration_date.required' => 'National ID expiration date is required.',
            'cards.national_id.expiration_date.after' => 'National ID must not be expired.',
            'cards.driving_license.expiration_date.required' => 'Driving license expiration date is required.',
            'cards.driving_license.expiration_date.after' => 'Driving license must not be expired.',
        ];
    }
}