<?php

namespace App\Models;

use App\Support\Enum\UserTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'phone',
    'password',
    'status',
    'balance',
    'device_token'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
    'device_token'
  ];

  protected $casts = [
    'balance' => 'decimal:2',
  ];

  // Relationships
  public function passenger()
  {
    return $this->hasOne(Passenger::class);
  }

  public function federation()
  {
    return $this->hasOne(Federation::class);
  }

  public function driver()
  {
    return $this->hasOne(Driver::class);
  }

  public function transactions()
  {
    return $this->morphMany(Transaction::class, 'entity');
  }

}
