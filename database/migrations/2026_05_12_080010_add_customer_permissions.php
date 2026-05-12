<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddCustomerPermissions extends Migration
{
    public function up()
    {
        if (Permission::count() === 0) {
            return;
        }

        $permissions = [
            'customer_create',
            'customer_edit',
            'customer_show',
            'customer_delete',
            'customer_access',
            'customer_address_create',
            'customer_address_edit',
            'customer_address_show',
            'customer_address_delete',
            'customer_address_access',
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
        $permissions = [
            'customer_create',
            'customer_edit',
            'customer_show',
            'customer_delete',
            'customer_access',
            'customer_address_create',
            'customer_address_edit',
            'customer_address_show',
            'customer_address_delete',
            'customer_address_access',
        ];

        Permission::whereIn('title', $permissions)->delete();
    }
}
