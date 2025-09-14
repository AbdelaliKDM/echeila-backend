<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Federation extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const string IMAGE = 'image';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'creation_date',
    ];

    protected $casts = [
        'creation_date' => 'datetime',
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

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'entity');
    }
}