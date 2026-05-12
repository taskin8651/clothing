<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCustomerRole extends Migration
{
    public function up()
    {
        if (DB::table('roles')->count() === 0) {
            return;
        }

        DB::table('roles')->insert([
            'title' => 'Customer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        DB::table('roles')->where('title', 'Customer')->delete();
    }
}
