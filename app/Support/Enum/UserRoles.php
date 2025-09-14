<?php
namespace App\Support\Enum;
class UserRoles
{
  const SUPER_ADMIN = 'super_admin';
  const ADMIN = 'admin';

  public static function lists():array
  {
    return [
      self::SUPER_ADMIN => self::SUPER_ADMIN,
      self::ADMIN => self::ADMIN,

    ];
  }

  public static function lists2(): array
  {
    return [
      self::SUPER_ADMIN => app()->isLocale('ar') ? 'سوبر أدمن' : 'Super Admin',
      self::ADMIN => app()->isLocale('ar') ? 'أدمن' : 'Admin',
    ];
  }

  public static function get_name(string $role):string
  {
    return self::lists2()[$role];
  }
}
