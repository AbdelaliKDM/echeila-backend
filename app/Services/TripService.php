<?php

namespace App\Services;

use Exception;
use App\Models\Trip;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Driver;
use App\Models\Location;
use App\Models\Passenger;
use App\Models\TripCargo;
use App\Models\TripClient;
use App\Constants\RideType;
use App\Constants\TripType;
use App\Constants\TripStatus;
use App\Models\TaxiRideDetail;
use App\Models\CarRescueDetail;
use App\Models\PaidDrivingDetail;
use Illuminate\Support\Facades\DB;
use App\Models\CargoTransportDetail;
use App\Models\WaterTransportDetail;
use App\Models\InternationalTripDetail;

class TripService
{
    /**
     * Create a new trip with its details and related data
     */
    public function createTrip(string $tripType, array $data, User $user): Trip
    {
        return DB::transaction(function () use ($tripType, $data, $user) {
            // Determine driver_id based on trip type
            $driverId = $this->handleDriver($tripType, $data, $user);

            // Create the main trip
            $trip = Trip::create([
                'driver_id' => $driverId,
                'type' => $tripType,
                'status' => TripStatus::PENDING,
                'note' => $data['note'] ?? null,
            ]);

            $this->createTripDetails($trip, $tripType, $data);

            // Add current user as client for non-international trips
            $this->handleClient($trip, $tripType, $data, $user);

            return $trip;
        });
    }

    /**
     * Determine the driver ID based on trip type and provided data
     */
    protected function handleDriver(string $tripType, array $data, User $user): int
    {
        if (in_array($tripType, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
            $data['driver_id'] = $data['driver_id'] ?? $user->driver->id;
        }

        $driver = Driver::findOrFail($data['driver_id']);

        if($driver->services()->where('trip_type',$tripType)->doesntExist()){
            throw new Exception("This driver does not provide {$tripType} service");
        }

        return $driver->id;
    }

    /**
     * Handle adding current user as trip client for non-international trips
     */
    protected function handleClient(Trip $trip, string $tripType, array $data, User $user): void
    {
        if(!in_array($tripType, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {

            TripClient::create([
                'trip_id' => $trip->id,
                'client_id' => $user->passenger->id,
                'client_type' => Passenger::class,
                'number_of_seats' => $data['number_of_seats'] ?? 1,
                'total_fees' => $data['total_fees'] ?? 0,
                'note' => $data['client_note'] ?? null,
            ]);
        }
        
    }


    /**
     * Create trip-specific details based on trip type
     */
    protected function createTripDetails(Trip $trip, string $tripType, array $data): void
    {
        match ($tripType) {
            TripType::TAXI_RIDE => $this->createTaxiRideDetail($trip, $data),
            TripType::CAR_RESCUE => $this->createCarRescueDetail($trip, $data),
            TripType::CARGO_TRANSPORT => $this->createCargoTransportDetail($trip, $data),
            TripType::WATER_TRANSPORT => $this->createWaterTransportDetail($trip, $data),
            TripType::PAID_DRIVING => $this->createPaidDrivingDetail($trip, $data),
            TripType::MRT_TRIP, TripType::ESP_TRIP => $this->createInternationalTripDetail($trip, $data),
            default => null,
        };
    }

    /**
     * Create taxi ride details
     */
    protected function createTaxiRideDetail(Trip $trip, array $data): void
    {
        $rideType = $data['ride_type'];

        if ($rideType === RideType::PRIVATE) {
            // For private rides, create Location records from coordinate data
            $startingLocation = Location::create([
                'name' => $data['starting_point']['name'],
                'latitude' => $data['starting_point']['latitude'],
                'longitude' => $data['starting_point']['longitude'],
            ]);

            $arrivalLocation = Location::create([
                'name' => $data['arrival_point']['name'],
                'latitude' => $data['arrival_point']['latitude'],
                'longitude' => $data['arrival_point']['longitude'],
            ]);

            TaxiRideDetail::create([
                'trip_id' => $trip->id,
                'starting_point_id' => $startingLocation->id,
                'starting_point_type' => Location::class,
                'arrival_point_id' => $arrivalLocation->id,
                'arrival_point_type' => Location::class,
                'ride_type' => $rideType,
            ]);
        } else {
            // For shared rides, use wilaya IDs
            TaxiRideDetail::create([
                'trip_id' => $trip->id,
                'starting_point_id' => $data['starting_point_id'],
                'starting_point_type' => 'App\\Models\\Wilaya',
                'arrival_point_id' => $data['arrival_point_id'],
                'arrival_point_type' => 'App\\Models\\Wilaya',
                'ride_type' => $rideType,
            ]);
        }
    }

    /**
     * Create car rescue details
     */
    protected function createCarRescueDetail(Trip $trip, array $data): void
    {
        CarRescueDetail::create([
            'trip_id' => $trip->id,
            'breakdown_point' => $data['breakdown_point'],
            'delivery_time' => $data['delivery_time'],
            'malfunction_type' => $data['malfunction_type'],
        ]);
    }

    /**
     * Create cargo transport details
     */
    protected function createCargoTransportDetail(Trip $trip, array $data): void
    {
        // Create the cargo record with description and weight
        $cargo = Cargo::create([
            'description' => $data['cargo']['description'],
            'weight' => $data['cargo']['weight'],
        ]);

        // Handle cargo images if provided
        if (isset($data['cargo']['images']) && is_array($data['cargo']['images'])) {
            foreach ($data['cargo']['images'] as $image) {
                $cargo->addMediaFromRequest('cargo.images.*')
                    ->toMediaCollection(Cargo::IMAGES);
            }
        }

        // Create the cargo transport detail
        CargoTransportDetail::create([
            'trip_id' => $trip->id,
            'delivery_point' => $data['delivery_point'],
            'delivery_time' => $data['delivery_time'],
        ]);

        // Create trip cargo relationship
        TripCargo::create([
            'trip_id' => $trip->id,
            'cargo_id' => $cargo->id,
            'total_fees' => $data['total_fees'] ?? 0,
        ]);
    }

    /**
     * Create water transport details
     */
    protected function createWaterTransportDetail(Trip $trip, array $data): void
    {
        WaterTransportDetail::create([
            'trip_id' => $trip->id,
            'delivery_point' => $data['delivery_point'],
            'delivery_time' => $data['delivery_time'],
            'water_type' => $data['water_type'],
            'quantity' => $data['quantity'],
        ]);
    }

    /**
     * Create paid driving details
     */
    protected function createPaidDrivingDetail(Trip $trip, array $data): void
    {
        // Create Location records from coordinate data
        $startingLocation = Location::create([
            'name' => $data['starting_point']['name'],
            'latitude' => $data['starting_point']['latitude'],
            'longitude' => $data['starting_point']['longitude'],
        ]);

        $arrivalLocation = Location::create([
            'name' => $data['arrival_point']['name'],
            'latitude' => $data['arrival_point']['latitude'],
            'longitude' => $data['arrival_point']['longitude'],
        ]);

        PaidDrivingDetail::create([
            'trip_id' => $trip->id,
            'starting_point' => $startingLocation->id,
            'arrival_point' => $arrivalLocation->id,
            'starting_time' => $data['starting_time'],
            'vehicle_type' => $data['vehicle_type'],
        ]);
    }

    /**
     * Create international trip details
     */
    protected function createInternationalTripDetail(Trip $trip, array $data): void
    {
        InternationalTripDetail::create([
            'trip_id' => $trip->id,
            'direction' => $data['direction'],
            'starting_place' => $data['starting_place'],
            'starting_time' => $data['starting_time'],
            'arrival_time' => $data['arrival_time'],
            'total_seats' => $data['total_seats'],
            'seat_price' => $data['seat_price'],
        ]);
    }

    /**
     * Create trip clients
     */
    protected function createTripClients(Trip $trip, array $clients): void
    {
        foreach ($clients as $clientData) {
            TripClient::create([
                'trip_id' => $trip->id,
                'client_id' => $clientData['client_id'],
                'client_type' => $clientData['client_type'],
                'number_of_seats' => $clientData['number_of_seats'] ?? 1,
                'total_fees' => $clientData['total_fees'],
                'note' => $clientData['note'] ?? null,
            ]);
        }
    }

    /**
     * Create trip cargos
     */
    protected function createTripCargos(Trip $trip, array $cargos): void
    {
        foreach ($cargos as $cargoData) {
            TripCargo::create([
                'trip_id' => $trip->id,
                'cargo_id' => $cargoData['cargo_id'],
                'total_fees' => $cargoData['total_fees'],
            ]);
        }
    }
}