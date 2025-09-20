<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $casts = [
        'name' => 'json',
    ];

    public function models()
    {
        return $this->hasMany(VehicleModel::class);
    }
}