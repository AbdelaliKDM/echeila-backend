<?php
namespace App\Support\Enum;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\select;

class Permissions
{
  //dashboard
  const MANAGE_ROLES = 'manage_roles';
  const MANAGE_PERMISSIONS = 'manage_permissions';
  const MANAGE_ADMINS = 'manage_admins';
  const MANAGE_USERS = 'manage_users';
  const MANAGE_SETTINGS = 'manage_settings';


  public static function lists():array
  {
    return [
      self::MANAGE_ROLES => self::MANAGE_ROLES,
      self::MANAGE_PERMISSIONS => self::MANAGE_PERMISSIONS,
       self::MANAGE_ADMINS => self::MANAGE_ADMINS,
      self::MANAGE_USERS => self::MANAGE_USERS,
      self::MANAGE_SETTINGS => self::MANAGE_SETTINGS,
    ];
  }

  public static function permission_slugs()
  {
    return [
      self::MANAGE_ROLES => 'roles',
      self::MANAGE_PERMISSIONS => 'permissions',
      self::MANAGE_ADMINS => 'admins',
      self::MANAGE_USERS => 'users',
      self::MANAGE_SETTINGS => 'settings',
    ];
  }

  public static function permission_arabic_slugs()
  {
    return [
      self::MANAGE_ROLES => 'الأدوار',
      self::MANAGE_PERMISSIONS => 'الصلاحيات',
      self::MANAGE_ADMINS => 'المسؤولين',
      self::MANAGE_USERS => 'المستخدمين',
      self::MANAGE_SETTINGS => 'الإعدادات',
    ];
  }

  public static function get_permission_slug($permission)
  {
    return app()->getLocale() == 'ar' ? self::permission_arabic_slugs()[$permission] : self::permission_slugs()[$permission];
  }
}
