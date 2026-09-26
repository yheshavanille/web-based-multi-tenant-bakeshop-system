<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Manual stock movements (restocks, adjustments, initial stock)
        Schema::create('inventory_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type', 30);              // stock_in, stock_out, adjustment
            $table->integer('quantity');             // signed: +2 or -3
            $table->integer('old_stock');            // before this movement
            $table->integer('new_stock');            // after this movement
            $table->text('notes')->nullable();       // human-entered reason
            $table->timestamps();

            $table->index(['product_id', 'branch_id', 'created_at']);
        });

        // ✅ Order-driven stock movements (sales, cancellations, no-shows)
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('status', 30);            // out, cancelled, no_show
            $table->integer('quantity');             // signed: -1 for sale, +1 for restore
            $table->integer('old_stock');            // before this movement
            $table->integer('new_stock');            // after this movement
            $table->text('notes')->nullable();       // auto-generated text
            $table->timestamps();

            $table->index(['order_id', 'created_at']);
            $table->index(['product_id', 'branch_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_histories');
        Schema::dropIfExists('inventory_histories');
    }
};
