<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddQuickCommercePermissions extends Migration
{
    public function up()
    {
        if (Role::count() === 0) {
            return;
        }

        $permissions = [
            'shop_access', 'shop_create', 'shop_edit', 'shop_show', 'shop_delete',
            'category_access', 'category_create', 'category_edit', 'category_show', 'category_delete',
            'product_access', 'product_create', 'product_edit', 'product_show', 'product_delete',
            'product_variant_access', 'product_variant_create', 'product_variant_edit', 'product_variant_show', 'product_variant_delete',
            'setting_access', 'setting_edit',
            'notification_access', 'notification_show', 'notification_mark_read', 'notification_delete',
            'delivery_zone_access', 'delivery_zone_create', 'delivery_zone_edit', 'delivery_zone_show', 'delivery_zone_delete',
            'homepage_section_access', 'homepage_section_create', 'homepage_section_edit', 'homepage_section_show', 'homepage_section_delete',
        ];

        $adminRole = Role::where('title', 'Admin')->first();

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
            'delivery_zone_access', 'delivery_zone_create', 'delivery_zone_edit', 'delivery_zone_show', 'delivery_zone_delete',
            'homepage_section_access', 'homepage_section_create', 'homepage_section_edit', 'homepage_section_show', 'homepage_section_delete',
        ])->delete();
    }
}
