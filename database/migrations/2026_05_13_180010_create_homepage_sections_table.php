<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomepageSectionsTable extends Migration
{
    public function up()
    {
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('type')->default('banner');
            $table->string('audience')->default('all');
            $table->string('placement')->default('home');
            $table->string('image')->nullable();
            $table->string('link_url')->nullable();
            $table->string('cta_text')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('homepage_sections');
    }
}
