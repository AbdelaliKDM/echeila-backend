<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LostAndFound extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

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