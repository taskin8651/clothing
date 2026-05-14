<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
        $user_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) != 'user_'
                && substr($permission->title, 0, 5) != 'role_'
                && substr($permission->title, 0, 11) != 'permission_'
                && substr($permission->title, 0, 9) != 'customer_'
                && substr($permission->title, 0, 13) != 'delivery_boy_'
                && substr($permission->title, 0, 18) != 'delivery_tracking_'
                && substr($permission->title, 0, 6) != 'order_'
                && substr($permission->title, 0, 8) != 'payment_'
                && substr($permission->title, 0, 8) != 'invoice_'
                && substr($permission->title, 0, 8) != 'receipt_'
                && substr($permission->title, 0, 15) != 'return_request_'
                && substr($permission->title, 0, 5) != 'shop_'
                && substr($permission->title, 0, 9) != 'category_'
                && substr($permission->title, 0, 8) != 'product_'
                && substr($permission->title, 0, 8) != 'setting_'
                && substr($permission->title, 0, 13) != 'notification_'
                && substr($permission->title, 0, 14) != 'delivery_zone_'
                && substr($permission->title, 0, 17) != 'homepage_section_';
        });
        Role::findOrFail(2)->permissions()->sync($user_permissions);
    }
}
