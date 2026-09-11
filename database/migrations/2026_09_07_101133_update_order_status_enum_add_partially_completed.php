<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Modify the status column to include new values
        DB::statement("ALTER TABLE orders MODIFY status ENUM(
            'pending',
            'preparing',
            'ready_for_pickup',
            'completed',
            'partially_completed',
            'cancelled',
            'no_show'
        ) DEFAULT 'pending'");

        // Modify the payment_status column to include new values
        DB::statement("ALTER TABLE orders MODIFY payment_status ENUM(
            'pending',
            'paid',
            'partially_paid',
            'refunded'
        ) DEFAULT 'pending'");
    }

    public function down()
    {
        // Revert back to original
        DB::statement("ALTER TABLE orders MODIFY status ENUM(
            'pending',
            'preparing',
            'ready_for_pickup',
            'completed',
            'cancelled',
            'no_show'
        ) DEFAULT 'pending'");

        DB::statement("ALTER TABLE orders MODIFY payment_status ENUM(
            'pending',
            'paid',
            'refunded'
        ) DEFAULT 'pending'");
    }
};
