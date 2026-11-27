<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('closure_request_status')->nullable();
            $table->text('closure_request_reason')->nullable();
            $table->timestamp('closure_requested_at')->nullable();
            $table->timestamp('closure_reviewed_at')->nullable();
            $table->text('closure_review_note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn([
                'closure_request_status',
                'closure_request_reason',
                'closure_requested_at',
                'closure_reviewed_at',
                'closure_review_note',
            ]);
        });
    }
};
