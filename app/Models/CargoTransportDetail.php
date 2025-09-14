<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoTransportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'delivery_point',
        'delivery_time',
    ];

    protected $casts = [
        'delivery_time' => 'datetime',
    ];

    // Relationships
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function deliveryLocation()
    {
        return $this->belongsTo(Location::class, 'delivery_point');
    }
}