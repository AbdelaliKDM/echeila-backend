<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model implements HasMedia
{
    use HasFactory;

    const string FRONT_IMAGE = 'front_image';
    const string BACK_IMAGE = 'back_image';

    protected $fillable = [
        'driver_id',
        'type',
        'expiration_date',
    ];

    protected $casts = [
        'expiration_date' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::FRONT_IMAGE)
            ->singleFile();

        $this->addMediaCollection(self::BACK_IMAGE)
            ->singleFile();
    }

    // Relationships
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
