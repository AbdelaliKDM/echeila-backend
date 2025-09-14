<?php

namespace App\Models;

use App\Constants\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_id',
        'entity_type',
        'trip_id',
        'type',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // Polymorphic relationship - entity can be passenger, driver, or federation
    public function entity()
    {
        return $this->morphTo();
    }
}