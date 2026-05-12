<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('shop_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable();

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->string('brand')->nullable();
            $table->string('fabric')->nullable();

            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();

            $table->integer('stock_quantity')->default(0);

            $table->boolean('try_cloth_available')->default(1);
            $table->boolean('return_available')->default(1);
            $table->boolean('is_featured')->default(0);
            $table->boolean('status')->default(1);

            $table->integer('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->nullOnDelete();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}