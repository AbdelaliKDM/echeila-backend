<?php

namespace App\Models;

use App\Constants\VehicleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaidDrivingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'starting_point',
        'arrival_point',
        'starting_time',
        'vehicle_type',
    ];

    protected $casts = [
        'starting_time' => 'datetime',
    ];

    // Relationships
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function startingLocation()
    {
        return $this->belongsTo(Location::class, 'starting_point');
    }

    public function arrivalLocation()
    {
        return $this->belongsTo(Location::class, 'arrival_point');
    }
}