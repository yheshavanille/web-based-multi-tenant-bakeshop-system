<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Add edit_count to service_reviews
        Schema::table('service_reviews', function (Blueprint $table) {
            $table->unsignedTinyInteger('edit_count')->default(0)->after('review');
        });

        // ✅ Add edit_count to product_reviews
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->unsignedTinyInteger('edit_count')->default(0)->after('review');
        });
    }

    public function down(): void
    {
        Schema::table('service_reviews', function (Blueprint $table) {
            $table->dropColumn('edit_count');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn('edit_count');
        });
    }
};
