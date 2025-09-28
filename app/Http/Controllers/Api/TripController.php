<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Trip;
use App\Constants\TripType;
use App\Services\TripService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
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
     * Get trips by type for the authenticated user (driver or passenger)
     */
    public function index(Request $request, string $type): JsonResponse
    {
        try {
            // Validate trip type
            if (!in_array($type, TripType::all())) {
                return $this->errorResponse('Invalid trip type', 400);
            }

            $user = auth()->user();
            $filters = $this->buildFilters($request, $type);

            // Determine if user is accessing as driver or passenger based on route
            $isDriverRoute = $request->route()->getPrefix() === 'api/v1/driver';
            
            if ($isDriverRoute) {
                $trips = $this->getDriverTrips($type, $filters, $user);
            } else {
                $trips = $this->getPassengerTrips($type, $filters, $user);
            }

            return $this->successResponse(TripResource::collection($trips));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Get trips for driver
     */
    protected function getDriverTrips(string $type, array $filters, $user)
    {
        $driver = $user->driver;
        
        if (!$driver) {
            throw new Exception('Driver profile not found');
        }

        $query = $this->tripService->getDriverTrips($type, $filters, $driver->id);
        
        return $query->paginate(15);
    }

    /**
     * Get trips for passenger
     */
    protected function getPassengerTrips(string $type, array $filters, $user)
    {
        $passenger = $user->passenger;
        
        if (!$passenger) {
            throw new Exception('Passenger profile not found');
        }

        $query = $this->tripService->getPassengerTrips($type, $filters, $passenger->id);
        
        return $query->paginate(15);
    }

    /**
     * Build filters array from request parameters
     */
    protected function buildFilters(Request $request, string $type): array
    {
        $filters = [];

        // Common filters for all trip types
        if ($request->has('status')) {
            $filters['status'] = $request->input('status');
        }

        if ($request->has('created_at_from') || $request->has('created_at_to')) {
            $filters['created_at'] = [];
            if ($request->has('created_at_from')) {
                $filters['created_at']['from'] = $request->input('created_at_from');
            }
            if ($request->has('created_at_to')) {
                $filters['created_at']['to'] = $request->input('created_at_to');
            }
        }

        // Trip type specific filters
        switch ($type) {
            case TripType::TAXI_RIDE:
                if ($request->has('type') && in_array($request->input('type'), ['shared', 'private'])) {
                    $filters['type'] = $request->input('type');
                }
                break;

            case TripType::CAR_RESCUE:
                if ($request->has('malfunction_type') && in_array($request->input('malfunction_type'), ['tire', 'fuel', 'battery', 'other'])) {
                    $filters['malfunction_type'] = $request->input('malfunction_type');
                }
                break;

            case TripType::PAID_DRIVING:
                if ($request->has('vehicle_type') && in_array($request->input('vehicle_type'), ['car', 'truck'])) {
                    $filters['vehicle_type'] = $request->input('vehicle_type');
                }
                break;

            case TripType::WATER_TRANSPORT:
                if ($request->has('water_type') && in_array($request->input('water_type'), ['drink', 'tea'])) {
                    $filters['water_type'] = $request->input('water_type');
                }
                break;

            case TripType::MRT_TRIP:
            case TripType::ESP_TRIP:
                if ($request->has('direction')) {
                    $filters['direction'] = $request->input('direction');
                }
                
                if ($request->has('type') && in_array($request->input('type'), ['cargo', 'client'])) {
                    $filters['type'] = $request->input('type');
                }
                break;
        }

        return $filters;
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