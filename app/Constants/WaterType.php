<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class WaterType
{
    const DRINK = 'drink';
    const TEA = 'tea';

    public static function all(): array
    {
        return [
            self::DRINK,
            self::TEA,
        ];
    }

    public static function all2(): array
    {
        return [
            self::DRINK => __('app.drink'),
            self::TEA => __('app.tea'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::DRINK => 'primary',
            self::TEA => 'success',
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
        return self::DRINK;
    }
}