<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayMongoService
{
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->secretKey = env('PAYMONGO_SECRET_KEY');
        $this->baseUrl = 'https://api.paymongo.com/v1';
    }

    /**
     * Create a checkout session for GCash/PayMaya
     */
    public function createPaymentIntent($order, $paymentType = null)
    {
        try {
            $payload = [
                'data' => [
                    'attributes' => [
                        'send_email_receipt' => false,
                        'show_description' => true,
                        'show_line_items' => true,
                        'line_items' => [
                            [
                                'currency' => 'PHP',
                                'amount' => (int) ($order->total_amount * 100),
                                'description' => 'Order #' . $order->order_number,
                                'name' => 'Web-based Multi-tenant Bakeshop System',
                                'quantity' => 1,
                            ]
                        ],
                        'payment_method_types' => ['gcash', 'paymaya'], // ✅ Always allow both
                        'reference_number' => $order->order_number,
                        'description' => 'Order #' . $order->order_number,
                        'success_url' => route('payment.success'),
                        'cancel_url' => route('payment.cancel'),
                        'metadata' => [
                            'order_id' => (string) $order->id,
                        ]
                    ]
                ]
            ];

            Log::info('PayMongo Checkout Session Request', ['payload' => $payload]);

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/checkout_sessions', $payload);

            Log::info('PayMongo Checkout Session Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (!isset($data['data']['id'])) {
                    throw new \Exception('Invalid response: missing checkout session ID');
                }

                $checkoutUrl = $data['data']['attributes']['checkout_url'] ?? null;

                if (!$checkoutUrl) {
                    throw new \Exception('No checkout URL found.');
                }

                $order->update([
                    'payment_intent_id' => $data['data']['id'],
                ]);

                $data['data']['attributes']['next_action']['redirect']['url'] = $checkoutUrl;

                Log::info('Checkout session created', [
                    'order_id' => $order->id,
                    'session_id' => $data['data']['id'],
                    'checkout_url' => $checkoutUrl,
                ]);

                return $data;
            }

            Log::warning('Checkout session failed, trying Source API fallback');
            return $this->createSourceFallback($order);
        } catch (\Exception $e) {
            Log::error('PayMongo checkout session creation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return $this->createSourceFallback($order);
        }
    }
    /**
     * Fallback: Create a payment source
     */
    private function createSourceFallback($order)
    {
        try {
            // ✅ Use payment_method_detail instead of payment_method
            $paymentMethod = $order->payment_method_detail ?? 'gcash';

            $payload = [
                'data' => [
                    'attributes' => [
                        'type' => $paymentMethod,
                        'amount' => (int) ($order->total_amount * 100),
                        'currency' => 'PHP',
                        'redirect' => [
                            'success' => route('payment.success'),
                            'failed' => route('payment.cancel'),
                        ],
                        'metadata' => [
                            'order_id' => (string) $order->id,
                        ]
                    ]
                ]
            ];

            Log::info('PayMongo Source Fallback Request', ['payload' => $payload]);

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/sources', $payload);

            Log::info('PayMongo Source Fallback Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $redirectUrl = $data['data']['attributes']['redirect']['url'] ?? null;

                if (!$redirectUrl) {
                    throw new \Exception('No redirect URL found in source fallback.');
                }

                $order->update([
                    'payment_intent_id' => $data['data']['id'],
                ]);

                $data['data']['attributes']['next_action']['redirect']['url'] = $redirectUrl;

                Log::info('Payment source created via fallback', [
                    'order_id' => $order->id,
                    'source_id' => $data['data']['id'],
                    'redirect_url' => $redirectUrl,
                ]);

                return $data;
            }

            $errorMessage = $this->extractErrorMessage($response);
            throw new \Exception($errorMessage);
        } catch (\Exception $e) {
            Log::error('PayMongo source fallback failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function extractErrorMessage($response)
    {
        $responseData = $response->json();
        if (isset($responseData['errors'][0]['detail'])) {
            return $responseData['errors'][0]['detail'];
        }
        if (isset($responseData['errors'][0]['code'])) {
            return $responseData['errors'][0]['code'] . ': ' . ($responseData['errors'][0]['detail'] ?? 'Unknown error');
        }
        return 'Payment creation failed: ' . $response->body();
    }

    public function isConfigured()
    {
        return !empty($this->secretKey) && str_starts_with($this->secretKey, 'sk_test_');
    }
}
