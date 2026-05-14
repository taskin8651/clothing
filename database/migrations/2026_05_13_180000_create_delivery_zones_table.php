<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryZonesTable extends Migration
{
    public function up()
    {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->string('city');
            $table->string('area')->nullable();
            $table->string('pincode');
            $table->unsignedSmallInteger('min_delivery_minutes')->default(60);
            $table->unsignedSmallInteger('max_delivery_minutes')->default(120);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('free_delivery_min_amount', 10, 2)->nullable();
            $table->boolean('try_first_enabled')->default(1);
            $table->unsignedSmallInteger('trial_wait_minutes')->default(30);
            $table->boolean('cod_enabled')->default(1);
            $table->boolean('status')->default(1);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('shop_id')->references('id')->on('shops')->nullOnDelete();
            $table->unique(['pincode', 'shop_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_zones');
    }
}
