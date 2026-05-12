<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryTrackingPermissions extends Migration
{
    public function up()
    {
        if (Role::count() === 0 || Permission::count() === 0) {
            return;
        }

        $permissions = [
            'delivery_tracking_access',
            'delivery_tracking_create',
            'delivery_tracking_edit',
            'delivery_tracking_show',
            'delivery_tracking_delete',
            'delivery_tracking_status_update',
            'delivery_tracking_assign',
            'delivery_tracking_cod_update',
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
            'delivery_tracking_access',
            'delivery_tracking_create',
            'delivery_tracking_edit',
            'delivery_tracking_show',
            'delivery_tracking_delete',
            'delivery_tracking_status_update',
            'delivery_tracking_assign',
            'delivery_tracking_cod_update',
        ])->delete();
    }
}
