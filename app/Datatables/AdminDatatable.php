<?php

namespace App\Datatables;

use Exception;
use App\Models\Admin;
use App\Support\Enum\Roles;
use App\Support\Enum\Permissions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\DataTableActionsTrait;

class AdminDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'admin',
            'email',
            'role',
            'actions',
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn('actions', function (Admin $admin) {
                    return $this
                        ->edit(route('admins.edit', $admin->id), Auth::user()->hasPermissionTo(Permissions::MANAGE_ADMINS))
                        ->delete($admin->id, Auth::user()->hasPermissionTo(Permissions::MANAGE_ADMINS))
                        ->make();
                })
                ->addColumn('admin', function (Admin $admin) {
                    return $this->thumbnailTitleMeta($admin->getFirstMediaUrl('image'), $admin->fullname, $admin->phone);
                })
                ->addColumn('email', function (Admin $admin) {
                    return $admin->email;
                })
                ->addColumn('role', function (Admin $admin) {
                    $role = $admin->getRoleNames()->first() ?? '-';

                    return $this->badge(Roles::get_color($role), Roles::get_name($role));
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this).' Error '.$e->getMessage());
        }
    }

    public function query($request)
    {
        $query = Admin::query();

        if ($request->role_filter) {
            $query->role($request->role_filter);
        }

        return $query->get();
    }
}
