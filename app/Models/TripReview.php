<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'passenger_id',
        'rating',
        'review_text',
    ];

    // Relationships
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }
}