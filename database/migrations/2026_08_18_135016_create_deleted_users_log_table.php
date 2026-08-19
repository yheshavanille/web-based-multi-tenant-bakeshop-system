<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deleted_users_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('original_user_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('roles')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamp('deleted_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deleted_users_log');
    }
};
