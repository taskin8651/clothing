<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddReturnRequestPermissions extends Migration
{
    public function up()
    {
        if (Role::count() === 0 || Permission::count() === 0) {
            return;
        }

        $permissions = [
            'return_request_access',
            'return_request_create',
            'return_request_edit',
            'return_request_show',
            'return_request_delete',
            'return_request_status_update',
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
            'return_request_access',
            'return_request_create',
            'return_request_edit',
            'return_request_show',
            'return_request_delete',
            'return_request_status_update',
        ])->delete();
    }
}
