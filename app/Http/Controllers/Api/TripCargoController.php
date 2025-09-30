<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Trip;
use App\Models\Cargo;
use App\Models\TripCargo;
use App\Constants\TripType;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripCargoResource;
use App\Http\Requests\Api\TripCargo\StoreTripCargoRequest;

class TripCargoController extends Controller
{
    use ApiResponseTrait, ImageUpload;

    /**
     * Get trip cargos for a specific trip
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

            $tripCargos = $trip->cargos()
                ->with(['cargo.passenger'])
                ->paginate(15);

            return $this->successResponse(
                TripCargoResource::collection($tripCargos)
            );

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a new trip cargo
     */
    public function store(StoreTripCargoRequest $request)
    {
        $validated = $this->validateRequest($request);

        try {

            $user = auth()->user();

            $trip = Trip::findOrFail($request->input('trip_id'));

            if (in_array($trip->type, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
                throw new Exception('This trip in not an international trip');
            }

            // Check if user has passenger profile
            if (!$user->passenger) {
                throw new Exception('User must have a passenger profile to add cargo');
            }

            DB::beginTransaction();

            // Create cargo for the current user
            $cargo = Cargo::create([
                'passenger_id' => $user->passenger->id,
                'description' => $validated['cargo']['description'],
                'weight' => $validated['cargo']['weight'],
            ]);

            // Handle cargo images if provided
            if ($request->hasFile('cargo.images')) {
                foreach ($request->file('cargo.images') as $image) {
                    $cargo->addMedia($image)
                        ->toMediaCollection(Cargo::IMAGES);
                }
            }

            // Create trip cargo
            $tripCargo = TripCargo::create([
                'trip_id' => $validated['trip_id'],
                'cargo_id' => $cargo->id,
                'total_fees' => $validated['total_fees'],
            ]);

            $tripCargo->load(['trip', 'cargo.passenger']);

            DB::commit();

            return $this->successResponse(
                new TripCargoResource($tripCargo)
            );

        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete a trip cargo
     */
    public function destroy($id)
    {
        try {
            // Check if the authenticated user is the cargo owner or the trip driver
            $user = auth()->user();
            $tripCargo = TripCargo::findOrFail($id);
            $isCargoOwner = false;
            $isDriver = false;

            // Check if user is the cargo owner
            if ($user->passenger && $tripCargo->cargo) {
                $isCargoOwner = $tripCargo->cargo->passenger_id === $user->passenger->id;
            }

            // Check if user is the trip driver
            if ($user->driver) {
                $isDriver = $tripCargo->trip->driver_id === $user->driver->id;
            }

            if (!$isCargoOwner && !$isDriver) {
                throw new Exception('Unauthorized to delete this trip cargo', 403);
            }

            $tripCargo->delete();

            return $this->successResponse();

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}