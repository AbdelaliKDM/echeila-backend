<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Trip;
use App\Constants\TripType;
use App\Services\TripService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use App\Http\Requests\Api\Trip\CreateTripRequest;

class TripController extends Controller
{
    use ApiResponseTrait;

    protected $tripService;

    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
    }

    /**
     * Get trips by type for the authenticated driver
     */
    public function index(string $type): JsonResponse
    {
        try {
            // Validate trip type
            if (!in_array($type, TripType::all())) {
                return $this->errorResponse('Invalid trip type', 400);
            }

            $driver = auth()->user()->driver;
            
            if (!$driver) {
                return $this->errorResponse('Driver profile not found', 404);
            }

            $trips = Trip::where('driver_id', $driver->id)
                ->where('type', $type)
                ->with(['details', 'clients.client', 'cargos.cargo'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return $this->successResponse(TripResource::collection($trips));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Create a new trip
     */
    public function store(CreateTripRequest $request, string $type): JsonResponse
    {

        $validated = $this->validateRequest($request);

        try {
            // Validate trip type
            if (!in_array($type, TripType::all())) {
                return $this->errorResponse('Invalid trip type', 400);
            }

            $trip = $this->tripService->createTrip($type, $validated, auth()->user());

            $trip->load(['details', 'clients', 'cargos']);

            return $this->successResponse(
                new TripResource($trip),
                'Trip created successfully',
                201
            );

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}