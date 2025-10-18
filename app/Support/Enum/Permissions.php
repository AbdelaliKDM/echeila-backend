<?php

namespace App\Support\Enum;

class Permissions
{
    // dashboard
    const MANAGE_ROLES = 'manage_roles';

    const MANAGE_PERMISSIONS = 'manage_permissions';

    const MANAGE_ADMINS = 'manage_admins';

    const MANAGE_USERS = 'manage_users';

    const MANAGE_SETTINGS = 'manage_settings';

    const MANAGE_PASSENGERS = 'manage_passengers';

    const MANAGE_DRIVERS = 'manage_drivers';

    const MANAGE_FEDERATIONS = 'manage_federations';

    const MANAGE_WILAYAS = 'manage_wilayas';

    const MANAGE_VEHICLES = 'manage_vehicles';

    public static function lists(): array
    {
        return [
            self::MANAGE_ROLES => self::MANAGE_ROLES,
            self::MANAGE_PERMISSIONS => self::MANAGE_PERMISSIONS,
            self::MANAGE_ADMINS => self::MANAGE_ADMINS,
            self::MANAGE_USERS => self::MANAGE_USERS,
            self::MANAGE_SETTINGS => self::MANAGE_SETTINGS,
            self::MANAGE_PASSENGERS => self::MANAGE_PASSENGERS,
            self::MANAGE_DRIVERS => self::MANAGE_DRIVERS,
            self::MANAGE_FEDERATIONS => self::MANAGE_FEDERATIONS,
            self::MANAGE_WILAYAS => self::MANAGE_WILAYAS,
            self::MANAGE_VEHICLES => self::MANAGE_VEHICLES,
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
            self::MANAGE_PASSENGERS => 'passengers',
            self::MANAGE_DRIVERS => 'drivers',
            self::MANAGE_FEDERATIONS => 'federations',
            self::MANAGE_WILAYAS => 'wilayas',
            self::MANAGE_VEHICLES => 'vehicles',
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
            self::MANAGE_PASSENGERS => 'الركاب',
            self::MANAGE_DRIVERS => 'السائقين',
            self::MANAGE_FEDERATIONS => 'الاتحاديات',
            self::MANAGE_WILAYAS => 'الولايات',
            self::MANAGE_VEHICLES => 'المركبات',
        ];
    }

    public static function get_permission_slug($permission)
    {
        return app()->getLocale() == 'ar' ? self::permission_arabic_slugs()[$permission] : self::permission_slugs()[$permission];
    }
}
