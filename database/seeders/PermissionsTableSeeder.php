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
            ['id' => 79, 'title' => 'shop_access'],
            ['id' => 80, 'title' => 'shop_create'],
            ['id' => 81, 'title' => 'shop_edit'],
            ['id' => 82, 'title' => 'shop_show'],
            ['id' => 83, 'title' => 'shop_delete'],
            ['id' => 84, 'title' => 'category_access'],
            ['id' => 85, 'title' => 'category_create'],
            ['id' => 86, 'title' => 'category_edit'],
            ['id' => 87, 'title' => 'category_show'],
            ['id' => 88, 'title' => 'category_delete'],
            ['id' => 89, 'title' => 'product_access'],
            ['id' => 90, 'title' => 'product_create'],
            ['id' => 91, 'title' => 'product_edit'],
            ['id' => 92, 'title' => 'product_show'],
            ['id' => 93, 'title' => 'product_delete'],
            ['id' => 94, 'title' => 'product_variant_access'],
            ['id' => 95, 'title' => 'product_variant_create'],
            ['id' => 96, 'title' => 'product_variant_edit'],
            ['id' => 97, 'title' => 'product_variant_show'],
            ['id' => 98, 'title' => 'product_variant_delete'],
            ['id' => 99, 'title' => 'setting_access'],
            ['id' => 100, 'title' => 'setting_edit'],
            ['id' => 101, 'title' => 'notification_access'],
            ['id' => 102, 'title' => 'notification_show'],
            ['id' => 103, 'title' => 'notification_mark_read'],
            ['id' => 104, 'title' => 'notification_delete'],
            ['id' => 105, 'title' => 'delivery_zone_access'],
            ['id' => 106, 'title' => 'delivery_zone_create'],
            ['id' => 107, 'title' => 'delivery_zone_edit'],
            ['id' => 108, 'title' => 'delivery_zone_show'],
            ['id' => 109, 'title' => 'delivery_zone_delete'],
            ['id' => 110, 'title' => 'homepage_section_access'],
            ['id' => 111, 'title' => 'homepage_section_create'],
            ['id' => 112, 'title' => 'homepage_section_edit'],
            ['id' => 113, 'title' => 'homepage_section_show'],
            ['id' => 114, 'title' => 'homepage_section_delete'],
        ];

        Permission::insert($permissions);
    }
}
