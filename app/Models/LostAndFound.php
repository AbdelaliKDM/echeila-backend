<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostAndFound extends Model implements HasMedia
{
    use HasFactory;

    const string IMAGE = 'image';

    protected $fillable = [
        'trip_id',
        'passenger_id',
        'description',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::IMAGE)
            ->singleFile();
    }

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