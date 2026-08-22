<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE order_items MODIFY status ENUM('pending', 'preparing', 'ready_for_pickup', 'completed', 'cancelled', 'no_show') DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE order_items MODIFY status ENUM('pending', 'preparing', 'ready_for_pickup', 'completed', 'no_show') DEFAULT 'pending'");
    }
};
