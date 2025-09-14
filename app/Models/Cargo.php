<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model implements HasMedia
{
    use HasFactory;

    const string IMAGE = 'image';

    protected $fillable = [
        'description',
        'weight',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::IMAGE)
            ->singleFile();
    }

    public function tripCargos()
    {
        return $this->hasMany(TripCargo::class);
    }
}