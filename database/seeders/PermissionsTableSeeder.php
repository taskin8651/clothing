<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 18,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 19,
                'title' => 'epaper_create',
            ],
            [
                'id'    => 20,
                'title' => 'epaper_edit',
            ],
            [
                'id'    => 21,
                'title' => 'epaper_show',
            ],
            [
                'id'    => 22,
                'title' => 'epaper_delete',
            ],
            [
                'id'    => 23,
                'title' => 'epaper_access',
            ],
            [
                'id'    => 24,
                'title' => 'profile_password_edit',
            ],
            [
                'id'    => 25,
                'title' => 'customer_create',
            ],
            [
                'id'    => 26,
                'title' => 'customer_edit',
            ],
            [
                'id'    => 27,
                'title' => 'customer_show',
            ],
            [
                'id'    => 28,
                'title' => 'customer_delete',
            ],
            [
                'id'    => 29,
                'title' => 'customer_access',
            ],
            [
                'id'    => 30,
                'title' => 'customer_address_create',
            ],
            [
                'id'    => 31,
                'title' => 'customer_address_edit',
            ],
            [
                'id'    => 32,
                'title' => 'customer_address_show',
            ],
            [
                'id'    => 33,
                'title' => 'customer_address_delete',
            ],
            [
                'id'    => 34,
                'title' => 'customer_address_access',
            ],
            [
                'id'    => 35,
                'title' => 'delivery_boy_access',
            ],
            [
                'id'    => 36,
                'title' => 'delivery_boy_create',
            ],
            [
                'id'    => 37,
                'title' => 'delivery_boy_edit',
            ],
            [
                'id'    => 38,
                'title' => 'delivery_boy_show',
            ],
            [
                'id'    => 39,
                'title' => 'delivery_boy_delete',
            ],
            [
                'id'    => 40,
                'title' => 'order_access',
            ],
            [
                'id'    => 41,
                'title' => 'order_create',
            ],
            [
                'id'    => 42,
                'title' => 'order_edit',
            ],
            [
                'id'    => 43,
                'title' => 'order_show',
            ],
            [
                'id'    => 44,
                'title' => 'order_delete',
            ],
            [
                'id'    => 45,
                'title' => 'order_status_update',
            ],
            [
                'id'    => 46,
                'title' => 'order_payment_update',
            ],
            [
                'id'    => 47,
                'title' => 'order_assign_delivery',
            ],
            [
                'id'    => 48,
                'title' => 'delivery_tracking_access',
            ],
            [
                'id'    => 49,
                'title' => 'delivery_tracking_create',
            ],
            [
                'id'    => 50,
                'title' => 'delivery_tracking_edit',
            ],
            [
                'id'    => 51,
                'title' => 'delivery_tracking_show',
            ],
            [
                'id'    => 52,
                'title' => 'delivery_tracking_delete',
            ],
            [
                'id'    => 53,
                'title' => 'delivery_tracking_status_update',
            ],
            [
                'id'    => 54,
                'title' => 'delivery_tracking_assign',
            ],
            [
                'id'    => 55,
                'title' => 'delivery_tracking_cod_update',
            ],
            [
                'id'    => 56,
                'title' => 'payment_access',
            ],
            [
                'id'    => 57,
                'title' => 'payment_edit',
            ],
            [
                'id'    => 58,
                'title' => 'payment_show',
            ],
            [
                'id'    => 59,
                'title' => 'payment_delete',
            ],
            [
                'id'    => 60,
                'title' => 'payment_status_update',
            ],
            ['id' => 61, 'title' => 'invoice_access'],
            ['id' => 62, 'title' => 'invoice_create'],
            ['id' => 63, 'title' => 'invoice_edit'],
            ['id' => 64, 'title' => 'invoice_show'],
            ['id' => 65, 'title' => 'invoice_delete'],
            ['id' => 66, 'title' => 'invoice_print'],
            ['id' => 67, 'title' => 'receipt_access'],
            ['id' => 68, 'title' => 'receipt_create'],
            ['id' => 69, 'title' => 'receipt_edit'],
            ['id' => 70, 'title' => 'receipt_show'],
            ['id' => 71, 'title' => 'receipt_delete'],
            ['id' => 72, 'title' => 'receipt_print'],
            ['id' => 73, 'title' => 'return_request_access'],
            ['id' => 74, 'title' => 'return_request_create'],
            ['id' => 75, 'title' => 'return_request_edit'],
            ['id' => 76, 'title' => 'return_request_show'],
            ['id' => 77, 'title' => 'return_request_delete'],
            ['id' => 78, 'title' => 'return_request_status_update'],
        ];

        Permission::insert($permissions);
    }
}
