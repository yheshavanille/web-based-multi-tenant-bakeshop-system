<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\BranchProduct;
use App\Models\StockHistory;
use App\Notifications\OrderStatusUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('PayMongo webhook received', ['payload' => $payload]);

        // Get the event type
        $eventType = $payload['data']['attributes']['type'] ?? null;

        if ($eventType === 'payment_intent.succeeded' || $eventType === 'checkout_session.payment_succeeded') {
            $paymentIntentId = $payload['data']['attributes']['data']['id'] ?? null;

            if ($paymentIntentId) {
                $this->handleSuccessfulPayment($paymentIntentId, $payload);
            }
        } elseif ($eventType === 'payment_intent.payment_failed') {
            $paymentIntentId = $payload['data']['attributes']['data']['id'] ?? null;

            if ($paymentIntentId) {
                $this->handleFailedPayment($paymentIntentId);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }

    private function handleSuccessfulPayment($paymentIntentId, $payload)
    {
        $order = Order::where('payment_intent_id', $paymentIntentId)->first();

        if (!$order) {
            Log::warning('Order not found for payment intent', ['payment_intent_id' => $paymentIntentId]);
            return;
        }

        // ✅ Get payment method from the webhook
        $paymentMethodType = null;
        $data = $payload['data']['attributes']['data'] ?? null;

        // Check for payment method in the payment intent
        if ($data && isset($data['attributes']['payment_method'])) {
            $paymentMethodType = $data['attributes']['payment_method'];
            Log::info('Payment method from checkout session', ['payment_method_type' => $paymentMethodType]);
        }

        // Alternative: check for payment method types used
        if (!$paymentMethodType && isset($data['attributes']['payment_method_types'])) {
            $types = $data['attributes']['payment_method_types'];
            if (is_array($types) && count($types) > 0) {
                $paymentMethodType = $types[0];
            }
            Log::info('Payment method from types', ['payment_method_type' => $paymentMethodType]);
        }

        // Fallback: check the order's existing payment_method_detail
        if (!$paymentMethodType) {
            $paymentMethodType = $order->payment_method_detail ?? 'gcash';
            Log::info('Using existing payment_method_detail', ['payment_method_type' => $paymentMethodType]);
        }

        Log::info('Final payment method type', ['payment_method_type' => $paymentMethodType]);

        // ✅ Update order with the actual payment method used
        $updateData = [
            'payment_status' => 'paid',
            'status' => 'preparing',
        ];

        // ✅ If payment method type is available, update payment_method_detail
        if ($paymentMethodType) {
            $updateData['payment_method_detail'] = $paymentMethodType;
        }

        $order->update($updateData);

        // ✅ Reduce stock when payment is confirmed
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        foreach ($orderItems as $item) {
            $branchProduct = BranchProduct::where('branch_id', $item->branch_id)
                ->where('product_id', $item->product_id)
                ->first();

            if ($branchProduct) {
                $oldStock = $branchProduct->stock;
                $branchProduct->decrement('stock', $item->quantity);

                StockHistory::create([
                    'product_id' => $item->product_id,
                    'branch_id' => $item->branch_id,
                    'user_id' => $order->customer_id,
                    'old_stock' => $oldStock,
                    'new_stock' => $branchProduct->stock,
                    'notes' => 'Order #' . $order->order_number . ' - Payment confirmed, stock reduced',
                ]);
            }
        }

        // ✅ Send notification to customer
        $customer = $order->customer;
        if ($customer) {
            Notification::send($customer, new OrderStatusUpdatedNotification(
                $order,
                'pending',
                'preparing'
            ));
        }

        Log::info('Payment succeeded and order updated', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'payment_method_type' => $paymentMethodType,
            'payment_intent_id' => $paymentIntentId,
        ]);
    }

    private function handleFailedPayment($paymentIntentId)
    {
        $order = Order::where('payment_intent_id', $paymentIntentId)->first();

        if (!$order) {
            Log::warning('Order not found for failed payment', ['payment_intent_id' => $paymentIntentId]);
            return;
        }

        $order->update([
            'payment_status' => 'failed',
            'status' => 'payment_failed',
        ]);

        Log::info('Payment failed for order', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'payment_intent_id' => $paymentIntentId,
        ]);
    }
}
