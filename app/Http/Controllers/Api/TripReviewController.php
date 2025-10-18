<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Trip;
use App\Models\Passenger;
use App\Models\TripReview;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripReviewResource;

class TripReviewController extends Controller
{
    use ApiResponseTrait;

    public function store(Request $request)
    {

        $validated = $this->validateRequest($request, [
                'trip_id' => 'required|exists:trips,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string'
            ]);
        try {
            $user = auth()->user();
            $passenger = $user->passenger;
            $trip = Trip::find($validated['trip_id']);

            if (!$passenger) {
                throw new Exception('Passenger profile not found', 404);
            }

            if($trip->clients()->where([ 'client_type' => Passenger::class, 'client_id' => $passenger->id])->doesntExist()) {
                throw new Exception('You did not participate in this trip', 400);
            }

                // Check if the passenger has already reviewed this trip
            if ($passenger->tripReviews()->where('trip_id', $validated['trip_id'])->exists()) {
                throw new Exception('You have already reviewed this trip', 400);
            }



            $review = $passenger->tripReviews()->create($validated);

            return $this->successResponse(new TripReviewResource($review));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}