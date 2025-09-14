<?php

namespace App\Constants\Statuses;

use Illuminate\Support\Collection;

class UserStatus
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    const BANNED = 'banned';

    public static function all():array
    {
        return [
          self::ACTIVE,
          self::INACTIVE,
          self::BANNED,
        ];
    }

    public static function all2():array
    {
        return [
          self::ACTIVE => __('app.active'),
          self::INACTIVE => __('app.inactive'),
          self::BANNED => __('app.banned'),
        ];
    }

    public static function colors(): array
    {
        return [
          self::ACTIVE => 'success',
          self::INACTIVE => 'danger',
          self::BANNED => 'warning',
        ];
    }

    public static function collection():Collection
    {
        return collect(array_combine(self::all(), self::all()));
    }

    public static function get(string $gender):string
    {
        return self::collection()->get($gender);
    }

    public static function get_name(string $status):string
    {
        return self::all2()[$status];
    }

    public static function get_color(string $status):string
    {
        return self::colors()[$status];
    }

    public static function default():string
    {
        return self::ACTIVE;
    }

}
