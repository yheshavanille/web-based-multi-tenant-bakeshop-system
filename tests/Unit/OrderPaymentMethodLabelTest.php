<?php

namespace Tests\Unit;

use App\Models\Order;
use PHPUnit\Framework\TestCase;

class OrderPaymentMethodLabelTest extends TestCase
{
    public function test_paymongo_orders_display_as_pay_as_you_order(): void
    {
        $order = new Order(['payment_method' => 'paymongo']);

        $this->assertSame('Pay as You Order', $order->payment_method_label);
        $this->assertTrue($order->isEPayment());
    }
}
