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

            // Create trip details first
            $detailsModel = $this->handleTripDetails($tripType, $data, $user);

            // Create the main trip with polymorphic relationship
            $trip = Trip::create([
                'driver_id' => $driverId,
                'type' => $tripType,
                'status' => TripStatus::PENDING,
                'note' => $data['note'] ?? null,
                'detailable_id' => $detailsModel->id,
                'detailable_type' => get_class($detailsModel),
            ]);

            // Add current user as client for non-international trips
            $this->handleClient($trip, $tripType, $data, $user);
            
            // Handle cargo creation and relationship for cargo transport trips
            $this->handleCargo($trip, $tripType, $data, $user);

            // Load relevant relationships based on trip type
            switch ($tripType) {
                case TripType::TAXI_RIDE:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'detailable.startingPoint',
                        'detailable.arrivalPoint'
                    ]);
                    break;

                case TripType::CAR_RESCUE:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'detailable.breakdownPoint'
                    ]);
                    break;

                case TripType::CARGO_TRANSPORT:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'cargo',
                        'detailable.deliveryPoint'
                    ]);
                    break;

                case TripType::WATER_TRANSPORT:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'detailable.deliveryPoint'
                    ]);
                    break;

                case TripType::PAID_DRIVING:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'detailable.startingPoint',
                        'detailable.arrivalPoint'
                    ]);
                    break;

                case TripType::MRT_TRIP:
                case TripType::ESP_TRIP:
                    $trip->load([
                        'driver',
                    ]);
                    break;

                default:
                    $trip->load(['driver', 'detailable']);
                    break;
            }

            return $trip;
        });
    }

    /**
     * Create trip-specific details based on trip type
     */
    protected function handleTripDetails(string $tripType, array $data, User $user)
    {
        return match ($tripType) {
            TripType::TAXI_RIDE => $this->createTaxiRideDetail($data),
            TripType::CAR_RESCUE => $this->createCarRescueDetail($data),
            TripType::CARGO_TRANSPORT => $this->createCargoTransportDetail($data),
            TripType::WATER_TRANSPORT => $this->createWaterTransportDetail($data),
            TripType::PAID_DRIVING => $this->createPaidDrivingDetail($data),
            TripType::MRT_TRIP, TripType::ESP_TRIP => $this->createInternationalTripDetail($data),
        };
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
     * Handle cargo creation and relationship for cargo transport trips
     */
    protected function handleCargo(Trip $trip, string $tripType, array $data, User $user): void
    {
        if ($tripType === TripType::CARGO_TRANSPORT) {
            // Create the cargo record with description, weight, and passenger_id
            $cargo = Cargo::create([
                'description' => $data['cargo']['description'],
                'weight' => $data['cargo']['weight'],
                'passenger_id' => $user->passenger->id,
            ]);

            // Handle cargo images if provided
            if (isset($data['cargo']['images']) && is_array($data['cargo']['images'])) {
                foreach ($data['cargo']['images'] as $image) {
                    $cargo->addMediaFromRequest('cargo.images.*')
                        ->toMediaCollection(Cargo::IMAGES);
                }
            }

            // Create trip cargo relationship
            TripCargo::create([
                'trip_id' => $trip->id,
                'cargo_id' => $cargo->id,
                'total_fees' => $data['total_fees'] ?? 0,
            ]);
        }
    }


    /**
     * Create taxi ride details
     */
    protected function createTaxiRideDetail(array $data): TaxiRideDetail
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

            return TaxiRideDetail::create([
                'starting_point_id' => $startingLocation->id,
                'starting_point_type' => Location::class,
                'arrival_point_id' => $arrivalLocation->id,
                'arrival_point_type' => Location::class,
                'ride_type' => $rideType,
            ]);
        } else {
            // For shared rides, use wilaya IDs
            return TaxiRideDetail::create([
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
    protected function createCarRescueDetail(array $data): CarRescueDetail
    {
        // Create Location record from coordinate data
        $breakdownLocation = Location::create([
            'name' => $data['breakdown_point']['name'],
            'latitude' => $data['breakdown_point']['latitude'],
            'longitude' => $data['breakdown_point']['longitude'],
        ]);

        return CarRescueDetail::create([
            'breakdown_point_id' => $breakdownLocation->id,
            'delivery_time' => $data['delivery_time'],
            'malfunction_type' => $data['malfunction_type'],
        ]);
    }

    /**
     * Create cargo transport details
     */
    protected function createCargoTransportDetail(array $data): CargoTransportDetail
    {

        // Create Location record from coordinate data
        $deliveryLocation = Location::create([
            'name' => $data['delivery_point']['name'],
            'latitude' => $data['delivery_point']['latitude'],
            'longitude' => $data['delivery_point']['longitude'],
        ]);

        // Create the cargo transport detail
        $cargoTransportDetail = CargoTransportDetail::create([
            'delivery_point_id' => $deliveryLocation->id,
            'delivery_time' => $data['delivery_time'],
        ]);

        return $cargoTransportDetail;
    }

    /**
     * Create water transport details
     */
    protected function createWaterTransportDetail(array $data): WaterTransportDetail
    {
        // Create Location record from coordinate data
        $deliveryLocation = Location::create([
            'name' => $data['delivery_point']['name'],
            'latitude' => $data['delivery_point']['latitude'],
            'longitude' => $data['delivery_point']['longitude'],
        ]);

        return WaterTransportDetail::create([
            'delivery_point_id' => $deliveryLocation->id,
            'delivery_time' => $data['delivery_time'],
            'water_type' => $data['water_type'],
            'quantity' => $data['quantity'],
        ]);
    }

    /**
     * Create paid driving details
     */
    protected function createPaidDrivingDetail(array $data): PaidDrivingDetail
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

        return PaidDrivingDetail::create([
            'starting_point_id' => $startingLocation->id,
            'arrival_point_id' => $arrivalLocation->id,
            'starting_time' => $data['starting_time'],
            'vehicle_type' => $data['vehicle_type'],
        ]);
    }

    /**
     * Create international trip details
     */
    protected function createInternationalTripDetail(array $data): InternationalTripDetail
    {
        return InternationalTripDetail::create([
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