<?php

namespace App\Http\Controllers\Api;

use App\Constants\TripType;
use Exception;
use App\Models\Trip;
use App\Models\TripClient;
use App\Models\Passenger;
use App\Models\Guest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripClientResource;
use App\Http\Requests\Api\TripClient\StoreTripClientRequest;

class TripClientController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get trip clients for a specific trip
     */
    public function index(Request $request)
    {
        $validated = $this->validateRequest($request, [
            'trip_id' => 'required|exists:trips,id'
        ]);

        try {

            $trip = Trip::findOrFail($request->input('trip_id'));

            if (in_array($trip->type, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
                throw new Exception('This trip in not an international trip');
            }

            $tripClients = $trip->clients()
                ->with(['client'])
                ->paginate(15);

            return $this->successResponse(
                TripClientResource::collection($tripClients)
            );

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a new trip client
     */
    public function store(StoreTripClientRequest $request)
    {
        $validated = $this->validateRequest($request);

        try {
            $user = auth()->user();

            // Get trip and calculate total fees
            $trip = Trip::with('detailable')->findOrFail($validated['trip_id']);

            if (in_array($trip->type, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
                throw new Exception('This trip in not an international trip');
            }

            $seatPrice = $trip->detailable->seat_price;
            $totalFees = $seatPrice * $validated['number_of_seats'];

            // Determine client type and ID
            if ($request->filled('fullname') && $request->filled('phone')) {
                // Create or find guest client
                $guest = Guest::firstOrCreate([
                    'fullname' => $validated['fullname'],
                    'phone' => $validated['phone'],
                ]);

                $clientId = $guest->id;
                $clientType = Guest::class;
            } else {
                // Use current user's passenger as client
                if (!$user->passenger) {
                    throw new Exception('User must have a passenger profile to book a trip');
                }

                $clientId = $user->passenger->id;
                $clientType = Passenger::class;
            }

            // Create trip client
            $tripClient = TripClient::create([
                'trip_id' => $validated['trip_id'],
                'client_id' => $clientId,
                'client_type' => $clientType,
                'number_of_seats' => $validated['number_of_seats'],
                'total_fees' => $totalFees,
                'note' => $validated['note'] ?? null,
            ]);

            $tripClient->load(['trip', 'client']);

            return $this->successResponse(
                new TripClientResource($tripClient)
            );

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete a trip client
     */
    public function destroy($id): JsonResponse
    {
        try {
            // Check if the authenticated user is the client or the trip driver
            $user = auth()->user();
            $tripClient = TripClient::findOrFail($id);
            $isClient = false;
            $isDriver = false;

            // Check if user is the client (only for passenger clients, not guests)
            if ($tripClient->client_type === Passenger::class && $user->passenger) {
                $isClient = $tripClient->client_id === $user->passenger->id;
            }

            // Check if user is the trip driver
            if ($user->driver) {
                $isDriver = $tripClient->trip->driver_id === $user->driver->id;
            }

            if (!$isClient && !$isDriver) {
                throw new Exception('Unauthorized to delete this trip client', 403);
            }

            $tripClient->delete();

            return $this->successResponse();

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}