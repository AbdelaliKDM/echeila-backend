<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;

class Notification extends BaseDatabaseNotification
{
    protected $table = 'notifications';

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function getTitleAttribute()
    {
        return app()->isLocale('fr') ? $this->data['title']['fr'] ?? ''
                : (app()->isLocale('ar') ? $this->data['title']['ar'] : $this->data['title']['en'] ?? '');
    }

    public function getBodyAttribute()
    {
        return app()->isLocale('fr') ? $this->data['body']['fr'] ?? ''
        : (app()->isLocale('ar') ? $this->data['body']['ar'] : $this->data['body']['en'] ?? '');
    }
}
