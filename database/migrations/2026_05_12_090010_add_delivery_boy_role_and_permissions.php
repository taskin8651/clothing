<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryBoyRoleAndPermissions extends Migration
{
    public function up()
    {
        if (Role::count() === 0 || Permission::count() === 0) {
            return;
        }

        Role::firstOrCreate(['title' => 'Delivery']);

        $permissions = [
            'delivery_boy_access',
            'delivery_boy_create',
            'delivery_boy_edit',
            'delivery_boy_show',
            'delivery_boy_delete',
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
            'delivery_boy_access',
            'delivery_boy_create',
            'delivery_boy_edit',
            'delivery_boy_show',
            'delivery_boy_delete',
        ])->delete();

        Role::where('title', 'Delivery')->delete();
    }
}
