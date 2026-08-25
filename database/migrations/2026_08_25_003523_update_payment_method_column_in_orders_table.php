<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // ✅ Update enum to include 'paymongo'
            $table->enum('payment_method', ['gcash', 'paymaya', 'paymongo', 'pickup_payment'])
                ->default('pickup_payment')
                ->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert back to original values
            $table->enum('payment_method', ['gcash', 'paymaya', 'pickup_payment'])
                ->default('pickup_payment')
                ->change();
        });
    }
};
