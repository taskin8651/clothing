<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'mobile')) {
                $table->string('mobile')->nullable()->unique()->after('email_verified_at');
            }

            if (! Schema::hasColumn('users', 'mobile_verified_at')) {
                $table->dateTime('mobile_verified_at')->nullable()->after('mobile');
            }

            if (! Schema::hasColumn('users', 'status')) {
                $table->boolean('status')->default(true)->after('password');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('users', 'mobile_verified_at')) {
                $table->dropColumn('mobile_verified_at');
            }

            if (Schema::hasColumn('users', 'mobile')) {
                $table->dropColumn('mobile');
            }
        });
    }
}
