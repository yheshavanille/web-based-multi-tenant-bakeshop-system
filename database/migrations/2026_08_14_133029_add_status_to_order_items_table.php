<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'status')) {
                $table->enum('status', ['pending', 'preparing', 'ready_for_pickup', 'completed'])->default('pending')->after('pickup_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
