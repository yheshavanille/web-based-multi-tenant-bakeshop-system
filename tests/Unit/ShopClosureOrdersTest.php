<?php

namespace Tests\Unit;

use App\Models\Shop;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ShopClosureOrdersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id');
            $table->boolean('is_parent_order')->default(false);
            $table->string('status');
            $table->string('payment_status');
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('shops');

        parent::tearDown();
    }

    public function test_only_unresolved_shop_orders_block_closure(): void
    {
        DB::table('shops')->insert(['id' => 1]);
        DB::table('orders')->insert([
            ['shop_id' => 1, 'is_parent_order' => false, 'status' => 'pending', 'payment_status' => 'paid'],
            ['shop_id' => 1, 'is_parent_order' => false, 'status' => 'preparing', 'payment_status' => 'paid'],
            ['shop_id' => 1, 'is_parent_order' => false, 'status' => 'ready_for_pickup', 'payment_status' => 'paid'],
            ['shop_id' => 1, 'is_parent_order' => false, 'status' => 'partially_completed', 'payment_status' => 'paid'],
            ['shop_id' => 1, 'is_parent_order' => false, 'status' => 'completed', 'payment_status' => 'pending'],
            ['shop_id' => 1, 'is_parent_order' => false, 'status' => 'completed', 'payment_status' => 'partially_paid'],
            ['shop_id' => 1, 'is_parent_order' => false, 'status' => 'completed', 'payment_status' => 'paid'],
            ['shop_id' => 1, 'is_parent_order' => false, 'status' => 'cancelled', 'payment_status' => 'pending'],
            ['shop_id' => 1, 'is_parent_order' => false, 'status' => 'no_show', 'payment_status' => 'partially_paid'],
            ['shop_id' => 1, 'is_parent_order' => true, 'status' => 'pending', 'payment_status' => 'pending'],
        ]);

        $shop = Shop::findOrFail(1);

        $this->assertSame([1, 2, 3, 4, 5, 6], $shop->unresolvedOrders()->orderBy('id')->pluck('id')->all());
        $this->assertTrue($shop->hasUnresolvedOrders());

        DB::table('orders')->delete();

        $this->assertFalse($shop->hasUnresolvedOrders());
    }
}
