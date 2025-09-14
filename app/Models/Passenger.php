<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Passenger extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const string IMAGE = 'image';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'birth_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function registerMediaCollections(): void
  {
    $this->addMediaCollection(self::IMAGE)
      ->singleFile();
  }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tripClients()
    {
        return $this->morphMany(TripClient::class, 'client');
    }

    public function tripReviews()
    {
        return $this->hasMany(TripReview::class);
    }

    public function lostAndFounds()
    {
        return $this->hasMany(LostAndFound::class);
    }
}
