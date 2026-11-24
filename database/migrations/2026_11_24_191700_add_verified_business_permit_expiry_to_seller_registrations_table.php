<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_registrations', function (Blueprint $table) {
            $table->date('verified_business_permit_expires_at')->nullable();
        });

        DB::table('seller_registrations')
            ->where('status', 'approved')
            ->whereNotNull('business_permit_expires_at')
            ->update([
                'verified_business_permit_expires_at' => DB::raw('business_permit_expires_at'),
            ]);
    }

    public function down(): void
    {
        Schema::table('seller_registrations', function (Blueprint $table) {
            $table->dropColumn('verified_business_permit_expires_at');
        });
    }
};
