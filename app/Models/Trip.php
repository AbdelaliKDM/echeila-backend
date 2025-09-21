<?php

namespace App\Models;

use App\Constants\TripType;
use App\Constants\TripStatus;
use App\Models\TaxiRideDetail;
use App\Models\CarRescueDetail;
use App\Models\PaidDrivingDetail;
use App\Models\CargoTransportDetail;
use App\Models\WaterTransportDetail;
use App\Models\InternationalTripDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'type',
        'status',
        'note',
    ];

    // Relationships
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function client()
    {
        return $this->hasOne(TripClient::class);
    }

    public function clients()
    {
        return $this->hasMany(TripClient::class);
    }

    public function cargos()
    {
        return $this->hasMany(TripCargo::class);
    }

    public function reviews()
    {
        return $this->hasMany(TripReview::class);
    }

    public function lostAndFounds()
    {
        return $this->hasMany(LostAndFound::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function details()
    {
        $class = match ($this->type) {
            TripType::TAXI_RIDE => TaxiRideDetail::class,
            TripType::CAR_RESCUE => CarRescueDetail::class,
            TripType::CARGO_TRANSPORT => CargoTransportDetail::class,
            TripType::WATER_TRANSPORT => WaterTransportDetail::class,
            TripType::PAID_DRIVING => PaidDrivingDetail::class,
            TripType::MRT_TRIP => InternationalTripDetail::class,
            TripType::ESP_TRIP => InternationalTripDetail::class,
            default => null,
        };

        return $class ? $this->hasOne($class) : null;
    }
}