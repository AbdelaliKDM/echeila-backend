<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class MalfunctionType
{
    const TIRE = 'tire';
    const BATTERY = 'battery';
    const FUEL = 'fuel';
    const OTHER = 'other';

    public static function all(): array
    {
        return [
            self::TIRE,
            self::BATTERY,
            self::FUEL,
            self::OTHER,
        ];
    }

    public static function all2(): array
    {
        return [
            self::TIRE => __('app.tire'),
            self::BATTERY => __('app.battery'),
            self::FUEL => __('app.fuel'),
            self::OTHER => __('app.other'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::TIRE => 'warning',
            self::BATTERY => 'danger',
            self::FUEL => 'info',
            self::OTHER => 'secondary',
        ];
    }

    public static function collection(): Collection
    {
        return collect(array_combine(self::all(), self::all()));
    }

    public static function get(string $type): string
    {
        return self::collection()->get($type);
    }

    public static function get_name(string $type): string
    {
        return self::all2()[$type];
    }

    public static function get_color(string $type): string
    {
        return self::colors()[$type];
    }

    public static function default(): string
    {
        return self::OTHER;
    }
}