<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class TripStatus
{
    const PENDING = 'pending';
    const ACCEPTED = 'accepted';
    const ONGOING = 'ongoing';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';

    public static function all(): array
    {
        return [
            self::PENDING,
            self::ACCEPTED,
            self::ONGOING,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }

    public static function all2(): array
    {
        return [
            self::PENDING => __('app.pending'),
            self::ACCEPTED => __('app.accepted'),
            self::ONGOING => __('app.ongoing'),
            self::COMPLETED => __('app.completed'),
            self::CANCELLED => __('app.cancelled'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::PENDING => 'warning',
            self::ACCEPTED => 'info',
            self::ONGOING => 'primary',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        ];
    }

    public static function collection(): Collection
    {
        return collect(array_combine(self::all(), self::all()));
    }

    public static function get(string $status): string
    {
        return self::collection()->get($status);
    }

    public static function get_name(string $status): string
    {
        return self::all2()[$status];
    }

    public static function get_color(string $status): string
    {
        return self::colors()[$status];
    }

    public static function default(): string
    {
        return self::PENDING;
    }
}