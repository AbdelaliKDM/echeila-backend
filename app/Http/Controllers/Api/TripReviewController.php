<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\Passenger;
use App\Models\TripReview;
use Illuminate\Http\Request;
use App\Constants\TripStatus;
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
            'reviewer_type' => 'required|in:driver,passenger',
            'reviewee_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        try {
            $user = auth()->user();
            $trip = Trip::find($validated['trip_id']);

            // Check if trip is completed
            if ($trip->status !== TripStatus::COMPLETED) {
                throw new Exception('You can only review completed trips', 400);
            }

            if($validated['reviewer_type'] === 'driver') {
                $reviewerType = Driver::class;
                $reviewer = $user->driver;
                $revieweeType = Passenger::class;
                $reviewee = Passenger::find($validated['reviewee_id']);

            } else {
                $reviewerType = Passenger::class;
                $reviewer = $user->passenger;
                $revieweeType = Driver::class;
                $reviewee = Driver::find($validated['reviewee_id']);

            }


            if (!$reviewer) {
                throw new Exception('User profile not found', 404);
            }

            if (!$reviewee) {
                throw new Exception('Reviewee not found', 404);
            }

            if($validated['reviewer_type'] === 'driver') {

                if ($trip->driver_id !== $reviewer->id) {
                    throw new Exception('You are not the driver of this trip', 400);
                }
                
                if ($trip->clients()->where(['client_type' => Passenger::class, 'client_id' => $reviewee->id])->doesntExist()) {
                    throw new Exception('Invalid reviewee for this trip', 400);
                }

            } else {

                if ($trip->clients()->where(['client_type' => Passenger::class, 'client_id' => $reviewer->id])->doesntExist()) {
                    throw new Exception('You did not participate in this trip', 400);
                }

                if ($trip->driver_id !== $reviewee->id) {
                    throw new Exception('Invalid reviewee for this trip', 400);
                }
            }

            // Check if already reviewed this person for this trip
            if ($reviewer->reviewsGiven()
                ->where('trip_id', $trip->id)
                ->where('reviewee_id', $reviewee->id)
                ->where('reviewee_type', $revieweeType)
                ->exists()) {
                throw new Exception('You have already reviewed this person for this trip', 400);
            }

            $review = TripReview::create([
                'trip_id' => $trip->id,
                'reviewer_id' => $reviewer->id,
                'reviewer_type' => $reviewerType,
                'reviewee_id' => $reviewee->id,
                'reviewee_type' => $revieweeType,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]);

            return $this->successResponse(new TripReviewResource($review));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}