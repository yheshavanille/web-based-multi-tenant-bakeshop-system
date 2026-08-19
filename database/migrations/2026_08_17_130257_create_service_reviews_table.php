<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->integer('rating')->unsigned(); // 1-5
            $table->integer('employee_rating')->unsigned()->nullable(); // 1-5
            $table->text('review')->nullable();
            $table->timestamps();

            // Ensure one review per order
            $table->unique('order_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_reviews');
    }
};
