<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class TransactionType
{
    const RESERVATION = 'reservation';
    const REFUND = 'refund';
    const DEPOSIT = 'deposit';
    const WITHDRAW = 'withdraw';
    const SUBSCRIBTION = 'subscribtion';
    const SERVICE = 'service';

    public static function all(): array
    {
        return [
            self::RESERVATION,
            self::REFUND,
            self::DEPOSIT,
            self::WITHDRAW,
            self::SUBSCRIBTION,
            self::SERVICE,
        ];
    }

    public static function all2(): array
    {
        return [
            self::RESERVATION => __('app.reservation'),
            self::REFUND => __('app.refund'),
            self::DEPOSIT => __('app.deposit'),
            self::WITHDRAW => __('app.withdraw'),
            self::SUBSCRIBTION => __('app.subscribtion'),
            self::SERVICE => __('app.service'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::RESERVATION => 'primary',
            self::REFUND => 'warning',
            self::DEPOSIT => 'success',
            self::WITHDRAW => 'info',
            self::SUBSCRIBTION => 'secondary',
            self::SERVICE => 'dark',
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
        return self::RESERVATION;
    }
}