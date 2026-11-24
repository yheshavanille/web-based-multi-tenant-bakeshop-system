<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('business_permit_path')->nullable();
            $table->date('business_permit_expires_at')->nullable();
            $table->string('pending_business_permit_path')->nullable();
            $table->date('pending_business_permit_expires_at')->nullable();
            $table->string('business_permit_review_status')->default('not_submitted');
            $table->text('business_permit_review_note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn([
                'business_permit_path',
                'business_permit_expires_at',
                'pending_business_permit_path',
                'pending_business_permit_expires_at',
                'business_permit_review_status',
                'business_permit_review_note',
            ]);
        });
    }
};
