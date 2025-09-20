<?php

namespace App\Models;

use App\Constants\DriverStatus;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const string IMAGE = 'image';

    protected $fillable = [
        'user_id',
        'federation_id',
        'first_name',
        'last_name',
        'birth_date',
        'email',
        'status',
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

    public function federation()
    {
        return $this->belongsTo(Federation::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'entity');
    }
}