<?php
namespace App\Support\Enum;
class UserTypes
{
  const ADMIN = 'admin';

  public static function lists():array
  {
    return [
      self::ADMIN => self::ADMIN,
    ];
  }

  public static function lists2(): array
  {
    return [
      self::ADMIN => app()->isLocale('ar') ? 'أدمن' : 'Admin',
    ];
  }

  public static function colors():array
  {
    return [
      self::ADMIN => 'primary',
    ];
  }

  public static function lists_arabic()
  {
    return [
      self::ADMIN => 'أدمن',
    ];
  }

  public static function get_arabic_name(string $type):string
  {
    return self::lists_arabic()[$type];
  }

  public static function get_name(string $type):string
  {
    return self::lists2()[$type];
  }

  public static function get_color(string $type):string
  {
    return self::colors()[$type];
  }
}
