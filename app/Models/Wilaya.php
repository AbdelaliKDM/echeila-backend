<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilaya extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function startingSeatPrices()
    {
        return $this->hasMany(SeatPrice::class, 'starting_wilaya_id');
    }

    public function arrivalSeatPrices()
    {
        return $this->hasMany(SeatPrice::class, 'arrival_wilaya_id');
    }

    // Polymorphic relationships
    public function taxiRideStartingPoints()
    {
        return $this->morphMany(TaxiRideDetail::class, 'starting_point');
    }

    public function taxiRideArrivalPoints()
    {
        return $this->morphMany(TaxiRideDetail::class, 'arrival_point');
    }
}