<?php
namespace App\Support\Enum;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\select;

class PermissionNames
{
  //dashboard
  const MANAGE_ROLES = 'manage_roles';
  const MANAGE_PERMISSIONS = 'manage_permissions';
  const MANAGE_USERS = 'manage_users';


  public static function lists():array
  {
    return [
      self::MANAGE_ROLES => self::MANAGE_ROLES,
      self::MANAGE_PERMISSIONS => self::MANAGE_PERMISSIONS,
      self::MANAGE_USERS => self::MANAGE_USERS,
    ];
  }

  public static function permission_slugs()
  {
    return [
      self::MANAGE_ROLES => 'roles',
      self::MANAGE_PERMISSIONS => 'permissions',
      self::MANAGE_USERS => 'users',
    ];
  }

  public static function permission_arabic_slugs()
  {
    return [
      self::MANAGE_ROLES => 'الأدوار',
      self::MANAGE_PERMISSIONS => 'الصلاحيات',
      self::MANAGE_USERS => 'المستخدمين',
    ];
  }

  public static function get_permission_slug($permission)
  {
    return app()->getLocale() == 'ar' ? self::permission_arabic_slugs()[$permission] : self::permission_slugs()[$permission];
  }
}
