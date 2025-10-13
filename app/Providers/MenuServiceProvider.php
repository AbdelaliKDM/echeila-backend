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
    MenuBuilder::header('Users & Roles');
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
        )
      ]
    );

    MenuBuilder::add(
      name: 'admins',
      slug: 'admins',
      route: 'admins.index',
      icon: 'bx bx-user',
      permission: ['manage_admins'],
    );

    MenuBuilder::add(
      name: 'users',
      slug: 'users',
      route: 'users.index',
      icon: 'bx bx-user',
      permission: ['manage_users'],
    );


  }
}
