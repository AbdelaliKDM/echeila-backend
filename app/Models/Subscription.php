<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'driver_id',
        'start_date',
        'end_date',
    ];

    // Relationships
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
