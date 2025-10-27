<?php

namespace App\Providers;

use App\Services\MenuBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->addSidebarMenuItems();
        $menuData = json_decode(json_encode([
            'verticalMenu' => MenuBuilder::get(),
            'horizontalMenu' => MenuBuilder::get(),
        ]));
        $this->app->make('view')->share('menuData', $menuData);
    }

    /**
     * Add sidebar menu items.
     */
    protected function addSidebarMenuItems(): void
    {
        MenuBuilder::add(
            name: 'dashboard',
            slug: 'dashboard',
            route: 'dashboard',
            icon: 'bx bx-home-circle',
        );
        //MenuBuilder::header('Users & Roles');
        MenuBuilder::add(
            name: 'roles-permissions',
            slug: ['roles', 'permissions'],
            icon: 'bx bx-shield',
            permission: ['manage_roles', 'manage_permissions'],
            submenu: [
                MenuBuilder::submenu(
                    name: 'roles',
                    slug: 'roles',
                    route: 'roles.index',
                    permission: ['manage_roles']
                ),
                MenuBuilder::submenu(
                    name: 'permissions',
                    slug: 'permissions',
                    route: 'permissions.index',
                    permission: ['manage_permissions']
                ),
            ]
        );

        MenuBuilder::add(
            name: 'admins',
            slug: 'admins',
            route: 'admins.index',
            icon: 'bx bxs-user',
            permission: ['manage_admins'],
        );

        MenuBuilder::add(
            name: 'users',
            slug: ['passengers', 'drivers', 'federations'],
            icon: 'bx bx-user',
            permission: ['manage_users'],
            submenu: [
                MenuBuilder::submenu(
                    name: 'passengers',
                    slug: 'passengers',
                    route: 'passengers.index',
                    permission: ['manage_passengers']
                ),
                MenuBuilder::submenu(
                    name: 'drivers',
                    slug: 'drivers',
                    route: 'drivers.index',
                    permission: ['manage_drivers']
                ),
                MenuBuilder::submenu(
                    name: 'federations',
                    slug: 'federations',
                    route: 'federations.index',
                    permission: ['manage_federations']
                ),
            ]
        );

        MenuBuilder::add(
            name: 'wilayas',
            slug: ['wilayas', 'seat-prices'],
            icon: 'bx bx-map',
            permission: ['manage_wilayas'],
            submenu: [
                MenuBuilder::submenu(
                    name: 'wilayas',
                    slug: 'wilayas',
                    route: 'wilayas.index',
                    permission: ['manage_wilayas']
                ),
                MenuBuilder::submenu(
                    name: 'seat-prices',
                    slug: 'seat-prices',
                    route: 'seat-prices.index',
                    permission: ['manage_wilayas']
                ),
            ]
        );

        MenuBuilder::add(
            name: 'vehicles',
            slug: ['brands', 'vehicle-models', 'colors'],
            icon: 'bx bx-car',
            permission: ['manage_vehicles'],
            submenu: [
                MenuBuilder::submenu(
                    name: 'brands',
                    slug: 'brands',
                    route: 'brands.index',
                    permission: ['manage_vehicles']
                ),
                MenuBuilder::submenu(
                    name: 'vehicle-models',
                    slug: 'vehicle-models',
                    route: 'vehicle-models.index',
                    permission: ['manage_vehicles']
                ),
                MenuBuilder::submenu(
                    name: 'colors',
                    slug: 'colors',
                    route: 'colors.index',
                    permission: ['manage_vehicles']
                )
            ]
        );

        MenuBuilder::add(
            name: 'trips',
            slug: ['admin/trips/all', 'admin/trips/taxi_ride', 'admin/trips/car_rescue', 'admin/trips/cargo_transport', 'admin/trips/water_transport', 'admin/trips/paid_driving', 'admin/trips/mrt_trip', 'admin/trips/esp_trip'],
            icon: 'bx bx-trip',
            permission: [],
            submenu: [
                MenuBuilder::submenu(
                    name: 'all_trips',
                    slug: 'admin/trips/all',
                    url: 'admin/trips/all',
                    permission: [],
                ),
                MenuBuilder::submenu(
                    name: 'taxi_rides',
                    slug: 'admin/trips/taxi_ride',
                    url: 'admin/trips/taxi_ride',
                    permission: [],
                ),
                MenuBuilder::submenu(
                    name: 'car_rescues',
                    slug: 'admin/trips/car_rescue',
                    url: 'admin/trips/car_rescue',
                    permission: [],
                ),
                MenuBuilder::submenu(
                    name: 'cargo_transports',
                    slug: 'admin/trips/cargo_transport',
                    url: 'admin/trips/cargo_transport',
                    permission: [],
                ),
                MenuBuilder::submenu(
                    name: 'water_transports',
                    slug: 'admin/trips/water_transport',
                    url: 'admin/trips/water_transport',
                    permission: [],
                ),
                MenuBuilder::submenu(
                    name: 'paid_drivings',
                    slug: 'admin/trips/paid_driving',
                    url: 'admin/trips/paid_driving',
                    permission: [],
                ),
                MenuBuilder::submenu(
                    name: 'mrt_trips',
                    slug: 'admin/trips/mrt_trip',
                    url: 'admin/trips/mrt_trip',
                    permission: [],
                ),
                MenuBuilder::submenu(
                    name: 'esp_trips',
                    slug: 'admin/trips/esp_trip',
                    url: 'admin/trips/esp_trip',
                    permission: [],
                ),
            ]
        );

        MenuBuilder::add(
            name: 'lost-and-founds',
            slug: 'lost-and-founds',
            route: 'lost-and-founds.index',
            icon: 'bx bx-search',
            permission: [],
        );

    }
}
