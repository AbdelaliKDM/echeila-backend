<?php

namespace App\Support\Enum;

class Permissions
{
    // Roles & Permissions
    const MANAGE_ROLES = 'manage_roles';
    const MANAGE_PERMISSIONS = 'manage_permissions';
    const MANAGE_SETTINGS = 'manage_settings';
    const MANAGE_NOTIFICATIONS = 'manage_notifications';
    const MANAGE_DOCUMENTATIONS = 'manage_documentations';

    // Admin permissions
    const ADMIN_INDEX = 'admin_index';
    const ADMIN_CREATE = 'admin_create';
    const ADMIN_UPDATE = 'admin_update';
    const ADMIN_DELETE = 'admin_delete';
    const ADMIN_ACTION_INDEX = 'adminAction_index';

    // Passenger permissions
    const PASSENGER_INDEX = 'passenger_index';
    const PASSENGER_SHOW = 'passenger_show';
    const PASSENGER_CHANGE_USER_STATUS = 'passenger_changeUserStatus';
    const PASSENGER_CHARGE_WALLET = 'passenger_chargeWallet';
    const PASSENGER_WITHDRAW_SUM = 'passenger_withdrawSum';

    // Driver permissions
    const DRIVER_INDEX = 'driver_index';
    const DRIVER_SHOW = 'driver_show';
    const DRIVER_CHANGE_STATUS = 'driver_changeStatus';
    const DRIVER_CHANGE_USER_STATUS = 'driver_changeUserStatus';
    const DRIVER_CHARGE_WALLET = 'driver_chargeWallet';
    const DRIVER_WITHDRAW_SUM = 'driver_withdrawSum';
    const DRIVER_PURCHASE_SUBSCRIPTION = 'driver_purchaseSubscription';

    // Federation permissions
    const FEDERATION_INDEX = 'federation_index';
    const FEDERATION_SHOW = 'federation_show';
    const FEDERATION_CREATE = 'federation_create';
    const FEDERATION_CHANGE_USER_STATUS = 'federation_changeUserStatus';

    // Wilaya permissions
    const WILAYA_INDEX = 'wilaya_index';
    const WILAYA_CREATE = 'wilaya_create';
    const WILAYA_UPDATE = 'wilaya_update';
    const WILAYA_DELETE = 'wilaya_delete';

    // Seat Price permissions
    const SEAT_PRICE_INDEX = 'seatPrice_index';
    const SEAT_PRICE_CREATE = 'seatPrice_create';
    const SEAT_PRICE_UPDATE = 'seatPrice_update';
    const SEAT_PRICE_DELETE = 'seatPrice_delete';

    // Brand permissions
    const BRAND_INDEX = 'brand_index';
    const BRAND_CREATE = 'brand_create';
    const BRAND_UPDATE = 'brand_update';
    const BRAND_DELETE = 'brand_delete';

    // Vehicle Model permissions
    const VEHICLE_MODEL_INDEX = 'vehicleModel_index';
    const VEHICLE_MODEL_CREATE = 'vehicleModel_create';
    const VEHICLE_MODEL_UPDATE = 'vehicleModel_update';
    const VEHICLE_MODEL_DELETE = 'vehicleModel_delete';

    // Color permissions
    const COLOR_INDEX = 'color_index';
    const COLOR_CREATE = 'color_create';
    const COLOR_UPDATE = 'color_update';
    const COLOR_DELETE = 'color_delete';

    // Lost and Found permissions
    const LOST_AND_FOUND_INDEX = 'lostAndFound_index';
    const LOST_AND_FOUND_UPDATE = 'lostAndFound_update';
    const LOST_AND_FOUND_DELETE = 'lostAndFound_delete';
    const LOST_AND_FOUND_CHANGE_STATUS = 'lostAndFound_changeStatus';

    // Trip permissions
    const ALL_TRIPS_INDEX = 'allTrips_index';
    
    const TAXI_RIDE_INDEX = 'taxiRide_index';
    const TAXI_RIDE_SHOW = 'taxiRide_show';
    
    const CAR_RESCUE_INDEX = 'carRescue_index';
    
    const CARGO_TRANSPORT_INDEX = 'cargoTransport_index';
    
    const WATER_TRANSPORT_INDEX = 'waterTransport_index';
    
    const PAID_DRIVING_INDEX = 'paidDriving_index';
    
    const MRT_TRIP_INDEX = 'mrtTrip_index';
    const MRT_TRIP_SHOW = 'mrtTrip_show';
    
    const ESP_TRIP_INDEX = 'espTrip_index';
    const ESP_TRIP_SHOW = 'espTrip_show';

    public static function lists(): array
    {
        return [
            self::MANAGE_ROLES => self::MANAGE_ROLES,
            self::MANAGE_PERMISSIONS => self::MANAGE_PERMISSIONS,
            self::MANAGE_SETTINGS => self::MANAGE_SETTINGS,
            self::MANAGE_NOTIFICATIONS => self::MANAGE_NOTIFICATIONS,
            self::MANAGE_DOCUMENTATIONS => self::MANAGE_DOCUMENTATIONS,
            
            // Admin
            self::ADMIN_INDEX => self::ADMIN_INDEX,
            self::ADMIN_CREATE => self::ADMIN_CREATE,
            self::ADMIN_UPDATE => self::ADMIN_UPDATE,
            self::ADMIN_DELETE => self::ADMIN_DELETE,
            self::ADMIN_ACTION_INDEX => self::ADMIN_ACTION_INDEX,
            
            // Passenger
            self::PASSENGER_INDEX => self::PASSENGER_INDEX,
            self::PASSENGER_SHOW => self::PASSENGER_SHOW,
            self::PASSENGER_CHANGE_USER_STATUS => self::PASSENGER_CHANGE_USER_STATUS,
            self::PASSENGER_CHARGE_WALLET => self::PASSENGER_CHARGE_WALLET,
            self::PASSENGER_WITHDRAW_SUM => self::PASSENGER_WITHDRAW_SUM,
            
            // Driver
            self::DRIVER_INDEX => self::DRIVER_INDEX,
            self::DRIVER_SHOW => self::DRIVER_SHOW,
            self::DRIVER_CHANGE_STATUS => self::DRIVER_CHANGE_STATUS,
            self::DRIVER_CHANGE_USER_STATUS => self::DRIVER_CHANGE_USER_STATUS,
            self::DRIVER_CHARGE_WALLET => self::DRIVER_CHARGE_WALLET,
            self::DRIVER_WITHDRAW_SUM => self::DRIVER_WITHDRAW_SUM,
            self::DRIVER_PURCHASE_SUBSCRIPTION => self::DRIVER_PURCHASE_SUBSCRIPTION,
            
            // Federation
            self::FEDERATION_INDEX => self::FEDERATION_INDEX,
            self::FEDERATION_SHOW => self::FEDERATION_SHOW,
            self::FEDERATION_CREATE => self::FEDERATION_CREATE,
            self::FEDERATION_CHANGE_USER_STATUS => self::FEDERATION_CHANGE_USER_STATUS,
            
            // Wilaya
            self::WILAYA_INDEX => self::WILAYA_INDEX,
            self::WILAYA_CREATE => self::WILAYA_CREATE,
            self::WILAYA_UPDATE => self::WILAYA_UPDATE,
            self::WILAYA_DELETE => self::WILAYA_DELETE,
            
            // Seat Price
            self::SEAT_PRICE_INDEX => self::SEAT_PRICE_INDEX,
            self::SEAT_PRICE_CREATE => self::SEAT_PRICE_CREATE,
            self::SEAT_PRICE_UPDATE => self::SEAT_PRICE_UPDATE,
            self::SEAT_PRICE_DELETE => self::SEAT_PRICE_DELETE,
            
            // Brand
            self::BRAND_INDEX => self::BRAND_INDEX,
            self::BRAND_CREATE => self::BRAND_CREATE,
            self::BRAND_UPDATE => self::BRAND_UPDATE,
            self::BRAND_DELETE => self::BRAND_DELETE,
            
            // Vehicle Model
            self::VEHICLE_MODEL_INDEX => self::VEHICLE_MODEL_INDEX,
            self::VEHICLE_MODEL_CREATE => self::VEHICLE_MODEL_CREATE,
            self::VEHICLE_MODEL_UPDATE => self::VEHICLE_MODEL_UPDATE,
            self::VEHICLE_MODEL_DELETE => self::VEHICLE_MODEL_DELETE,
            
            // Color
            self::COLOR_INDEX => self::COLOR_INDEX,
            self::COLOR_CREATE => self::COLOR_CREATE,
            self::COLOR_UPDATE => self::COLOR_UPDATE,
            self::COLOR_DELETE => self::COLOR_DELETE,
            
            // Lost and Found
            self::LOST_AND_FOUND_INDEX => self::LOST_AND_FOUND_INDEX,
            self::LOST_AND_FOUND_UPDATE => self::LOST_AND_FOUND_UPDATE,
            self::LOST_AND_FOUND_DELETE => self::LOST_AND_FOUND_DELETE,
            self::LOST_AND_FOUND_CHANGE_STATUS => self::LOST_AND_FOUND_CHANGE_STATUS,
            
            // Trips
            self::ALL_TRIPS_INDEX => self::ALL_TRIPS_INDEX,
            self::TAXI_RIDE_INDEX => self::TAXI_RIDE_INDEX,
            self::TAXI_RIDE_SHOW => self::TAXI_RIDE_SHOW,
            self::CAR_RESCUE_INDEX => self::CAR_RESCUE_INDEX,
            self::CARGO_TRANSPORT_INDEX => self::CARGO_TRANSPORT_INDEX,
            self::WATER_TRANSPORT_INDEX => self::WATER_TRANSPORT_INDEX,
            self::PAID_DRIVING_INDEX => self::PAID_DRIVING_INDEX,
            self::MRT_TRIP_INDEX => self::MRT_TRIP_INDEX,
            self::MRT_TRIP_SHOW => self::MRT_TRIP_SHOW,
            self::ESP_TRIP_INDEX => self::ESP_TRIP_INDEX,
            self::ESP_TRIP_SHOW => self::ESP_TRIP_SHOW,
        ];
    }

    public static function permission_slugs()
    {
        return [
            // Roles & Permissions
            self::MANAGE_ROLES => 'roles',
            self::MANAGE_PERMISSIONS => 'permissions',
            self::MANAGE_SETTINGS => 'settings',
            self::MANAGE_NOTIFICATIONS => 'notifications',
            self::MANAGE_DOCUMENTATIONS => 'documentations',
            
            // Admin
            self::ADMIN_INDEX => 'admins',
            self::ADMIN_CREATE => 'create admin',
            self::ADMIN_UPDATE => 'update admin',
            self::ADMIN_DELETE => 'delete admin',
            self::ADMIN_ACTION_INDEX => 'admin actions',
            
            // Passenger
            self::PASSENGER_INDEX => 'passengers',
            self::PASSENGER_SHOW => 'show passenger',
            self::PASSENGER_CHANGE_USER_STATUS => 'change passenger status',
            self::PASSENGER_CHARGE_WALLET => 'charge passenger wallet',
            self::PASSENGER_WITHDRAW_SUM => 'withdraw from passenger',
            
            // Driver
            self::DRIVER_INDEX => 'drivers',
            self::DRIVER_SHOW => 'show driver',
            self::DRIVER_CHANGE_STATUS => 'change driver status',
            self::DRIVER_CHANGE_USER_STATUS => 'change driver user status',
            self::DRIVER_CHARGE_WALLET => 'charge driver wallet',
            self::DRIVER_WITHDRAW_SUM => 'withdraw from driver',
            self::DRIVER_PURCHASE_SUBSCRIPTION => 'purchase subscription',
            
            // Federation
            self::FEDERATION_INDEX => 'federations',
            self::FEDERATION_SHOW => 'show federation',
            self::FEDERATION_CREATE => 'create federation',
            self::FEDERATION_CHANGE_USER_STATUS => 'change federation status',
            
            // Wilaya
            self::WILAYA_INDEX => 'wilayas',
            self::WILAYA_CREATE => 'create wilaya',
            self::WILAYA_UPDATE => 'update wilaya',
            self::WILAYA_DELETE => 'delete wilaya',
            
            // Seat Price
            self::SEAT_PRICE_INDEX => 'seat prices',
            self::SEAT_PRICE_CREATE => 'create seat price',
            self::SEAT_PRICE_UPDATE => 'update seat price',
            self::SEAT_PRICE_DELETE => 'delete seat price',
            
            // Brand
            self::BRAND_INDEX => 'brands',
            self::BRAND_CREATE => 'create brand',
            self::BRAND_UPDATE => 'update brand',
            self::BRAND_DELETE => 'delete brand',
            
            // Vehicle Model
            self::VEHICLE_MODEL_INDEX => 'vehicle models',
            self::VEHICLE_MODEL_CREATE => 'create vehicle model',
            self::VEHICLE_MODEL_UPDATE => 'update vehicle model',
            self::VEHICLE_MODEL_DELETE => 'delete vehicle model',
            
            // Color
            self::COLOR_INDEX => 'colors',
            self::COLOR_CREATE => 'create color',
            self::COLOR_UPDATE => 'update color',
            self::COLOR_DELETE => 'delete color',
            
            // Lost and Found
            self::LOST_AND_FOUND_INDEX => 'lost and found',
            self::LOST_AND_FOUND_UPDATE => 'update lost and found',
            self::LOST_AND_FOUND_DELETE => 'delete lost and found',
            self::LOST_AND_FOUND_CHANGE_STATUS => 'change lost and found status',
            
            // Trips
            self::ALL_TRIPS_INDEX => 'all trips',
            self::TAXI_RIDE_INDEX => 'taxi rides',
            self::TAXI_RIDE_SHOW => 'show taxi ride',
            self::CAR_RESCUE_INDEX => 'car rescue',
            self::CARGO_TRANSPORT_INDEX => 'cargo transport',
            self::WATER_TRANSPORT_INDEX => 'water transport',
            self::PAID_DRIVING_INDEX => 'paid driving',
            self::MRT_TRIP_INDEX => 'MRT trips',
            self::MRT_TRIP_SHOW => 'show MRT trip',
            self::ESP_TRIP_INDEX => 'ESP trips',
            self::ESP_TRIP_SHOW => 'show ESP trip',
        ];
    }

    public static function permission_arabic_slugs()
    {
        return [
            // Roles & Permissions
            self::MANAGE_ROLES => 'الأدوار',
            self::MANAGE_PERMISSIONS => 'الصلاحيات',
            self::MANAGE_SETTINGS => 'الإعدادات',
            self::MANAGE_NOTIFICATIONS => 'الإشعارات',
            self::MANAGE_DOCUMENTATIONS => 'المستندات',
            
            // Admin
            self::ADMIN_INDEX => 'المسؤولين',
            self::ADMIN_CREATE => 'إنشاء مسؤول',
            self::ADMIN_UPDATE => 'تحديث مسؤول',
            self::ADMIN_DELETE => 'حذف مسؤول',
            self::ADMIN_ACTION_INDEX => 'إجراءات المسؤولين',
            
            // Passenger
            self::PASSENGER_INDEX => 'الركاب',
            self::PASSENGER_SHOW => 'عرض راكب',
            self::PASSENGER_CHANGE_USER_STATUS => 'تغيير حالة الراكب',
            self::PASSENGER_CHARGE_WALLET => 'شحن محفظة الراكب',
            self::PASSENGER_WITHDRAW_SUM => 'سحب من الراكب',
            
            // Driver
            self::DRIVER_INDEX => 'السائقين',
            self::DRIVER_SHOW => 'عرض سائق',
            self::DRIVER_CHANGE_STATUS => 'تغيير حالة السائق',
            self::DRIVER_CHANGE_USER_STATUS => 'تغيير حالة مستخدم السائق',
            self::DRIVER_CHARGE_WALLET => 'شحن محفظة السائق',
            self::DRIVER_WITHDRAW_SUM => 'سحب من السائق',
            self::DRIVER_PURCHASE_SUBSCRIPTION => 'شراء اشتراك',
            
            // Federation
            self::FEDERATION_INDEX => 'الاتحادات',
            self::FEDERATION_SHOW => 'عرض اتحاد',
            self::FEDERATION_CREATE => 'إنشاء اتحاد',
            self::FEDERATION_CHANGE_USER_STATUS => 'تغيير حالة الاتحاد',
            
            // Wilaya
            self::WILAYA_INDEX => 'الولايات',
            self::WILAYA_CREATE => 'إنشاء ولاية',
            self::WILAYA_UPDATE => 'تحديث ولاية',
            self::WILAYA_DELETE => 'حذف ولاية',
            
            // Seat Price
            self::SEAT_PRICE_INDEX => 'أسعار المقاعد',
            self::SEAT_PRICE_CREATE => 'إنشاء سعر مقعد',
            self::SEAT_PRICE_UPDATE => 'تحديث سعر مقعد',
            self::SEAT_PRICE_DELETE => 'حذف سعر مقعد',
            
            // Brand
            self::BRAND_INDEX => 'العلامات التجارية',
            self::BRAND_CREATE => 'إنشاء علامة تجارية',
            self::BRAND_UPDATE => 'تحديث علامة تجارية',
            self::BRAND_DELETE => 'حذف علامة تجارية',
            
            // Vehicle Model
            self::VEHICLE_MODEL_INDEX => 'موديلات المركبات',
            self::VEHICLE_MODEL_CREATE => 'إنشاء موديل مركبة',
            self::VEHICLE_MODEL_UPDATE => 'تحديث موديل مركبة',
            self::VEHICLE_MODEL_DELETE => 'حذف موديل مركبة',
            
            // Color
            self::COLOR_INDEX => 'الألوان',
            self::COLOR_CREATE => 'إنشاء لون',
            self::COLOR_UPDATE => 'تحديث لون',
            self::COLOR_DELETE => 'حذف لون',
            
            // Lost and Found
            self::LOST_AND_FOUND_INDEX => 'المفقودات',
            self::LOST_AND_FOUND_UPDATE => 'تحديث مفقودات',
            self::LOST_AND_FOUND_DELETE => 'حذف مفقودات',
            self::LOST_AND_FOUND_CHANGE_STATUS => 'تغيير حالة المفقودات',
            
            // Trips
            self::ALL_TRIPS_INDEX => 'جميع الرحلات',
            self::TAXI_RIDE_INDEX => 'رحلات التاكسي',
            self::TAXI_RIDE_SHOW => 'عرض رحلة تاكسي',
            self::CAR_RESCUE_INDEX => 'إنقاذ السيارات',
            self::CARGO_TRANSPORT_INDEX => 'نقل البضائع',
            self::WATER_TRANSPORT_INDEX => 'نقل المياه',
            self::PAID_DRIVING_INDEX => 'القيادة المدفوعة',
            self::MRT_TRIP_INDEX => 'رحلات MRT',
            self::MRT_TRIP_SHOW => 'عرض رحلة MRT',
            self::ESP_TRIP_INDEX => 'رحلات ESP',
            self::ESP_TRIP_SHOW => 'عرض رحلة ESP',
        ];
    }

    public static function get_permission_slug($permission)
    {
        return app()->getLocale() == 'ar' ? self::permission_arabic_slugs()[$permission] : self::permission_slugs()[$permission];
    }
}
