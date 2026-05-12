<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddOrderPermissions extends Migration
{
    public function up()
    {
        if (Role::count() === 0 || Permission::count() === 0) {
            return;
        }

        $permissions = [
            'order_access',
            'order_create',
            'order_edit',
            'order_show',
            'order_delete',
            'order_status_update',
            'order_payment_update',
            'order_assign_delivery',
        ];

        $adminRole = Role::find(1);

        foreach ($permissions as $title) {
            $permission = Permission::firstOrCreate(['title' => $title]);

            if ($adminRole) {
                $adminRole->permissions()->syncWithoutDetaching([$permission->id]);
            }
        }
    }

    public function down()
    {
        Permission::whereIn('title', [
            'order_access',
            'order_create',
            'order_edit',
            'order_show',
            'order_delete',
            'order_status_update',
            'order_payment_update',
            'order_assign_delivery',
        ])->delete();
    }
}
