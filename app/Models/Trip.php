<?php

namespace App\Models;

use App\Constants\TripStatus;
use App\Constants\TripType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'trip_type',
        'status',
        'note',
    ];

    // Relationships
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function tripClients()
    {
        return $this->hasMany(TripClient::class);
    }

    public function tripCargos()
    {
        return $this->hasMany(TripCargo::class);
    }

    public function tripReviews()
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

    // Polymorphic relationships for trip details
    public function taxiRideDetails()
    {
        return $this->hasOne(TaxiRideDetail::class);
    }

    public function carRescueDetails()
    {
        return $this->hasOne(CarRescueDetail::class);
    }

    public function cargoTransportDetails()
    {
        return $this->hasOne(CargoTransportDetail::class);
    }

    public function waterTransportDetails()
    {
        return $this->hasOne(WaterTransportDetail::class);
    }

    public function paidDrivingDetails()
    {
        return $this->hasOne(PaidDrivingDetail::class);
    }

    public function internationalTripDetails()
    {
        return $this->hasOne(InternationalTripDetail::class);
    }
}