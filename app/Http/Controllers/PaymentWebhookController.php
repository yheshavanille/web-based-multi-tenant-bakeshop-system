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
        $checkoutSessionId = $payload['data']['attributes']['data']['id'] ?? null;

        if ($eventType === 'checkout_session.payment_succeeded') {
            if ($checkoutSessionId) {
                $this->handleSuccessfulPayment($checkoutSessionId, $payload);
            }
        } elseif ($eventType === 'checkout_session.payment_failed') {
            if ($checkoutSessionId) {
                $this->handleFailedPayment($checkoutSessionId);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }

    private function handleSuccessfulPayment($checkoutSessionId, $payload)
    {
        // Find the parent order by payment_intent_id (which stores the checkout session ID)
        $parentOrder = Order::where('payment_intent_id', $checkoutSessionId)
            ->where('is_parent_order', true)
            ->first();

        if (!$parentOrder) {
            Log::warning('Parent order not found for checkout session', ['checkout_session_id' => $checkoutSessionId]);

            // Fallback: try to find any order with this payment_intent_id
            $order = Order::where('payment_intent_id', $checkoutSessionId)->first();
            if ($order) {
                Log::info('Found regular order instead of parent order', ['order_id' => $order->id]);
                $this->updateOrderAndChildren($order, $payload);
            }
            return;
        }

        Log::info('Parent order found', [
            'parent_order_id' => $parentOrder->id,
            'order_number' => $parentOrder->order_number,
        ]);

        // Update parent order
        $this->updateOrderAndChildren($parentOrder, $payload);
    }

    private function updateOrderAndChildren($order, $payload)
    {
        // Get payment method from webhook
        $paymentMethodType = $this->extractPaymentMethod($payload);

        // Update the order (parent or regular)
        $updateData = [
            'payment_status' => 'paid',
            'status' => 'preparing',
        ];

        if ($paymentMethodType) {
            $updateData['payment_method_detail'] = $paymentMethodType;
        }

        $order->update($updateData);

        // If this is a parent order, update all child orders
        if ($order->is_parent_order) {
            Log::info('Updating child orders for parent', ['parent_order_id' => $order->id]);

            Order::where('parent_order_id', $order->id)->update([
                'payment_status' => 'paid',
                'status' => 'preparing',
                'payment_method_detail' => $paymentMethodType,
            ]);

            // Process stock reduction for each child order
            $childOrders = Order::where('parent_order_id', $order->id)->get();
            foreach ($childOrders as $childOrder) {
                $this->reduceStockForOrder($childOrder);
                $this->notifyOrderManagers($childOrder);
            }
        } else {
            // Regular order - reduce stock and notify
            $this->reduceStockForOrder($order);
            $this->notifyOrderManagers($order);
        }

        // Send notification to customer
        $customer = $order->customer;
        if ($customer) {
            Notification::send($customer, new OrderStatusUpdatedNotification(
                $order,
                'pending',
                'preparing'
            ));
        }

        Log::info('Payment succeeded and order(s) updated', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'is_parent' => $order->is_parent_order,
            'payment_method_type' => $paymentMethodType,
        ]);
    }

    private function reduceStockForOrder($order)
    {
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
    }

    private function notifyOrderManagers($order)
    {
        // Get order managers for this shop
        $orderManagers = \App\Models\Employee::where('shop_id', $order->shop_id)
            ->where('role', 'order_manager')
            ->where('is_active', true)
            ->with('user')
            ->get();

        $users = $orderManagers->pluck('user')->filter();

        if ($users->count() > 0) {
            Notification::send($users, new OrderStatusUpdatedNotification(
                $order,
                'pending',
                'preparing'
            ));
        }

        // Also notify owner
        $owner = $order->shop->user;
        if ($owner) {
            Notification::send($owner, new OrderStatusUpdatedNotification(
                $order,
                'pending',
                'preparing'
            ));
        }
    }

    private function extractPaymentMethod($payload)
    {
        $data = $payload['data']['attributes']['data'] ?? null;

        // Check for payment method in the payment intent
        if ($data && isset($data['attributes']['payment_method'])) {
            return $data['attributes']['payment_method'];
        }

        // Check for payment method types
        if ($data && isset($data['attributes']['payment_method_types'])) {
            $types = $data['attributes']['payment_method_types'];
            if (is_array($types) && count($types) > 0) {
                return $types[0];
            }
        }

        // Check for source type
        if ($data && isset($data['attributes']['source']['type'])) {
            return $data['attributes']['source']['type'];
        }

        return null;
    }

    private function handleFailedPayment($checkoutSessionId)
    {
        $parentOrder = Order::where('payment_intent_id', $checkoutSessionId)
            ->where('is_parent_order', true)
            ->first();

        if (!$parentOrder) {
            $order = Order::where('payment_intent_id', $checkoutSessionId)->first();
            if ($order) {
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'payment_failed',
                ]);
            }
            return;
        }

        $parentOrder->update([
            'payment_status' => 'failed',
            'status' => 'payment_failed',
        ]);

        // Update all child orders
        Order::where('parent_order_id', $parentOrder->id)->update([
            'payment_status' => 'failed',
            'status' => 'payment_failed',
        ]);

        Log::info('Payment failed for parent order', [
            'parent_order_id' => $parentOrder->id,
            'checkout_session_id' => $checkoutSessionId,
        ]);
    }
}
