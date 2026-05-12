<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title')->nullable();
            $table->text('message')->nullable();

            $table->string('type')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();

            $table->nullableMorphs('notifiable');
            $table->nullableMorphs('related');

            $table->string('action_url')->nullable();
            $table->json('data')->nullable();

            $table->boolean('is_read')->default(0);
            $table->timestamp('read_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}