<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoTransportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_point',
        'delivery_time',
    ];

    protected $casts = [
        'delivery_time' => 'datetime',
    ];

    // Relationships
    public function trip()
    {
        return $this->morphOne(Trip::class, 'detailable');
    }

    public function deliveryLocation()
    {
        return $this->belongsTo(Location::class, 'delivery_point');
    }
}