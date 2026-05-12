<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Company
            $table->string('company_name')->nullable();
            $table->string('site_title')->nullable();
            $table->string('support_email')->nullable();
            $table->string('support_phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('gst_number')->nullable();

            // Address
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pincode')->nullable();

            // Branding
            $table->string('site_logo')->nullable();
            $table->string('site_favicon')->nullable();
            $table->string('invoice_logo')->nullable();

            // Prefixes
            $table->string('invoice_prefix')->nullable();
            $table->string('receipt_prefix')->nullable();
            $table->string('order_prefix')->nullable();
            $table->string('return_prefix')->nullable();
            $table->string('tracking_prefix')->nullable();

            // Invoice
            $table->text('invoice_terms')->nullable();
            $table->text('invoice_footer_note')->nullable();

            // Order / Delivery
            $table->decimal('default_tax_percent', 10, 2)->default(0);
            $table->decimal('default_delivery_charge', 10, 2)->default(0);
            $table->decimal('free_delivery_min_amount', 10, 2)->default(0);
            $table->integer('return_window_days')->default(7);

            $table->boolean('allow_return_if_try_cloth')->default(0);
            $table->boolean('cod_enabled')->default(1);
            $table->boolean('online_payment_enabled')->default(1);

            // Payment Gateway
            $table->string('payment_gateway_name')->nullable();
            $table->string('payment_gateway_key')->nullable();
            $table->string('payment_gateway_secret')->nullable();

            // Social
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('linkedin_url')->nullable();

            // SEO
            $table->string('default_meta_title')->nullable();
            $table->text('default_meta_description')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
}