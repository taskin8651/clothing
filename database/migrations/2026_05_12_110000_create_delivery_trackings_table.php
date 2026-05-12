<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryTrackingsTable extends Migration
{
    public function up()
    {
        Schema::create('delivery_trackings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tracking_number')->unique();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->unsignedBigInteger('delivery_boy_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('customer_address_id')->nullable();
            $table->text('pickup_address')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('city')->nullable();
            $table->string('area')->nullable();
            $table->string('pincode')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('status')->default('pending');
            $table->decimal('cod_amount', 10, 2)->default(0);
            $table->boolean('cod_collected')->default(false);
            $table->timestamp('cod_collected_at')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('pickup_pending_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('out_for_delivery_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('failed_delivery_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('failure_reason')->nullable();
            $table->text('delivery_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')->references('id')->on('orders')->nullOnDelete();
            $table->foreign('shop_id')->references('id')->on('shops')->nullOnDelete();
            $table->foreign('delivery_boy_id')->references('id')->on('delivery_boys')->nullOnDelete();
            $table->foreign('customer_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('customer_address_id')->references('id')->on('user_addresses')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_trackings');
    }
}
