<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 12, 2)->nullable()->after('branch_id');
            $table->decimal('tax_amount', 12, 2)->nullable()->after('subtotal');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'tax_amount']);
        });
    }
};
