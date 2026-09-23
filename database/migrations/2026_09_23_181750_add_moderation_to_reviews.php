<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Moderation columns for service_reviews
        Schema::table('service_reviews', function (Blueprint $table) {
            // Moderation status: visible (default) | pending_review | kept | removed
            $table->string('moderation_status', 20)->default('visible')->after('review');

            // Flag metadata
            $table->foreignId('flagged_by')->nullable()->after('moderation_status')
                ->constrained('users')->onDelete('set null');
            $table->string('flag_reason', 100)->nullable()->after('flagged_by');
            $table->text('flag_notes')->nullable()->after('flag_reason');
            $table->timestamp('flagged_at')->nullable()->after('flag_notes');

            // Moderator decision metadata
            $table->foreignId('moderated_by')->nullable()->after('flagged_at')
                ->constrained('users')->onDelete('set null');
            $table->text('moderator_notes')->nullable()->after('moderated_by');
            $table->timestamp('moderated_at')->nullable()->after('moderator_notes');

            $table->index('moderation_status');
        });

        // ✅ Moderation columns for product_reviews
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->string('moderation_status', 20)->default('visible')->after('review');

            $table->foreignId('flagged_by')->nullable()->after('moderation_status')
                ->constrained('users')->onDelete('set null');
            $table->string('flag_reason', 100)->nullable()->after('flagged_by');
            $table->text('flag_notes')->nullable()->after('flag_reason');
            $table->timestamp('flagged_at')->nullable()->after('flag_notes');

            $table->foreignId('moderated_by')->nullable()->after('flagged_at')
                ->constrained('users')->onDelete('set null');
            $table->text('moderator_notes')->nullable()->after('moderated_by');
            $table->timestamp('moderated_at')->nullable()->after('moderator_notes');

            $table->index('moderation_status');
        });

        // ✅ Add a "review_banned_at" flag on users to prevent them from leaving new reviews
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('review_banned_at')->nullable()->after('is_active');
            $table->text('review_ban_reason')->nullable()->after('review_banned_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['review_banned_at', 'review_ban_reason']);
        });

        Schema::table('service_reviews', function (Blueprint $table) {
            $table->dropForeign(['flagged_by']);
            $table->dropForeign(['moderated_by']);
            $table->dropColumn([
                'moderation_status',
                'flagged_by',
                'flag_reason',
                'flag_notes',
                'flagged_at',
                'moderated_by',
                'moderator_notes',
                'moderated_at',
            ]);
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropForeign(['flagged_by']);
            $table->dropForeign(['moderated_by']);
            $table->dropColumn([
                'moderation_status',
                'flagged_by',
                'flag_reason',
                'flag_notes',
                'flagged_at',
                'moderated_by',
                'moderator_notes',
                'moderated_at',
            ]);
        });
    }
};
