<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class TripType
{
    const TAXI_RIDE = 'taxi_ride';
    const CAR_RESCUE = 'car_rescue';
    const CARGO_TRANSPORT = 'cargo_transport';
    const WATER_TRANSPORT = 'water_transport';
    const PAID_DRIVING = 'paid_driving';
    const MRT_TRIP = 'mrt_trip';
    const ESP_TRIP = 'esp_trip';

    public static function all(): array
    {
        return [
            self::TAXI_RIDE,
            self::CAR_RESCUE,
            self::CARGO_TRANSPORT,
            self::WATER_TRANSPORT,
            self::PAID_DRIVING,
            self::MRT_TRIP,
            self::ESP_TRIP,
        ];
    }

    public static function all2(): array
    {
        return [
            self::TAXI_RIDE => __('app.taxi_ride'),
            self::CAR_RESCUE => __('app.car_rescue'),
            self::CARGO_TRANSPORT => __('app.cargo_transport'),
            self::WATER_TRANSPORT => __('app.water_transport'),
            self::PAID_DRIVING => __('app.paid_driving'),
            self::MRT_TRIP => __('app.mrt_trip'),
            self::ESP_TRIP => __('app.esp_trip'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::TAXI_RIDE => 'primary',
            self::CAR_RESCUE => 'warning',
            self::CARGO_TRANSPORT => 'info',
            self::WATER_TRANSPORT => 'secondary',
            self::PAID_DRIVING => 'success',
            self::MRT_TRIP => 'dark',
            self::ESP_TRIP => 'danger',
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
        return self::TAXI_RIDE;
    }
}