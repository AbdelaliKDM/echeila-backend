<?php

namespace App\Models;

use App\Constants\MalfunctionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRescueDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'breakdown_point',
        'delivery_time',
        'malfunction_type',
    ];

    protected $casts = [
        'delivery_time' => 'datetime',
    ];

    // Relationships
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function breakdownLocation()
    {
        return $this->belongsTo(Location::class, 'breakdown_point');
    }
}