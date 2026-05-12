<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceReceiptPermissions extends Migration
{
    public function up()
    {
        if (Role::count() === 0 || Permission::count() === 0) {
            return;
        }

        $permissions = [
            'invoice_access', 'invoice_create', 'invoice_edit', 'invoice_show', 'invoice_delete', 'invoice_print',
            'receipt_access', 'receipt_create', 'receipt_edit', 'receipt_show', 'receipt_delete', 'receipt_print',
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
            'invoice_access', 'invoice_create', 'invoice_edit', 'invoice_show', 'invoice_delete', 'invoice_print',
            'receipt_access', 'receipt_create', 'receipt_edit', 'receipt_show', 'receipt_delete', 'receipt_print',
        ])->delete();
    }
}
