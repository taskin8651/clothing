<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddPaymentPermissions extends Migration
{
    public function up()
    {
        if (Role::count() === 0 || Permission::count() === 0) {
            return;
        }

        $permissions = [
            'payment_access',
            'payment_edit',
            'payment_show',
            'payment_delete',
            'payment_status_update',
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
            'payment_access',
            'payment_edit',
            'payment_show',
            'payment_delete',
            'payment_status_update',
        ])->delete();
    }
}
