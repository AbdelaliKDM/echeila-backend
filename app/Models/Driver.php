<?php

namespace App\Models;

use App\Constants\DriverStatus;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model implements HasMedia
{
    use HasFactory;

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

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'entity');
    }
}