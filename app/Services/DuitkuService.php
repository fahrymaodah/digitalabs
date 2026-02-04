<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DuitkuService
{
    protected string $merchantCode;
    protected string $apiKey;
    protected bool $sandbox;
    protected array $endpoints;

    public function __construct()
    {
        $this->merchantCode = config('duitku.merchant_code');
        $this->apiKey = config('duitku.api_key');
        $this->sandbox = config('duitku.sandbox', true);
        $this->endpoints = config('duitku.endpoints.' . ($this->sandbox ? 'sandbox' : 'production'));
    }

    /**
     * Get available payment methods from Duitku
     */
    public function getPaymentMethods(int $amount): array
    {
        $datetime = date('Y-m-d H:i:s');
        $signature = hash('sha256', $this->merchantCode . $amount . $datetime . $this->apiKey);

        $payload = [
            'merchantcode' => $this->merchantCode,
            'amount' => $amount,
            'datetime' => $datetime,
            'signature' => $signature,
        ];

        try {
            $response = Http::post($this->endpoints['payment_methods'], $payload);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['paymentFee'])) {
                    return [
                        'success' => true,
                        'payment_methods' => $data['paymentFee'],
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data['Message'] ?? 'Failed to get payment methods',
                ];
            }

            return [
                'success' => false,
                'message' => 'HTTP Error: ' . $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Duitku getPaymentMethods error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create payment invoice / request transaction
     */
    public function createInvoice(Order $order, string $paymentMethod): array
    {
        $merchantOrderId = $order->order_number;
        $amount = (int) $order->total;
        $callbackUrl = url(config('duitku.callback_url'));
        $returnUrl = url(config('duitku.return_url'));
        $expiryPeriod = config('duitku.expiry_period');

        // Build signature
        $signature = md5($this->merchantCode . $merchantOrderId . $amount . $this->apiKey);

        // Get user info
        $user = $order->user;

        // Build item details - MUST match paymentAmount exactly
        // Use single item with total amount to avoid calculation issues
        $courseTitles = $order->items->map(fn($item) => $item->course->title ?? 'Course')->implode(', ');
        
        $itemDetails = [
            [
                'name' => Str::limit($courseTitles, 100),
                'price' => (int) $amount,
                'quantity' => 1,
            ]
        ];

        $payload = [
            'merchantCode' => $this->merchantCode,
            'paymentAmount' => $amount,
            'paymentMethod' => $paymentMethod,
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => 'Pembelian Course di Digitalabs',
            'additionalParam' => '',
            'merchantUserInfo' => $user->email ?? '',
            'customerVaName' => $user->name ?? 'Customer',
            'email' => $user->email ?? '',
            'phoneNumber' => $user->phone ?? '',
            'itemDetails' => $itemDetails,
            'customerDetail' => [
                'firstName' => $user->name ?? 'Customer',
                'lastName' => '',
                'email' => $user->email ?? '',
                'phoneNumber' => $user->phone ?? '',
            ],
            'callbackUrl' => $callbackUrl,
            'returnUrl' => $returnUrl,
            'signature' => $signature,
            'expiryPeriod' => $expiryPeriod,
        ];

        try {
            $response = Http::post($this->endpoints['inquiry'], $payload);

            Log::info('Duitku createInvoice request', ['payload' => $payload]);
            Log::info('Duitku createInvoice response', ['response' => $response->json()]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['paymentUrl'])) {
                    // Update order with Duitku reference
                    $order->update([
                        'payment_method' => $paymentMethod,
                        'duitku_reference' => $data['reference'] ?? null,
                        'duitku_payment_url' => $data['paymentUrl'],
                        'duitku_response' => $data,
                        'expired_at' => now()->addMinutes($expiryPeriod),
                    ]);

                    return [
                        'success' => true,
                        'payment_url' => $data['paymentUrl'],
                        'reference' => $data['reference'] ?? null,
                        'va_number' => $data['vaNumber'] ?? null,
                        'qr_string' => $data['qrString'] ?? null,
                        'amount' => $data['amount'] ?? $amount,
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data['Message'] ?? 'Failed to create invoice',
                    'status_code' => $data['statusCode'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => 'HTTP Error: ' . $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Duitku createInvoice error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check transaction status
     */
    public function checkStatus(string $merchantOrderId): array
    {
        $signature = md5($this->merchantCode . $merchantOrderId . $this->apiKey);

        $payload = [
            'merchantCode' => $this->merchantCode,
            'merchantOrderId' => $merchantOrderId,
            'signature' => $signature,
        ];

        try {
            $response = Http::post($this->endpoints['check_status'], $payload);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'status_code' => $data['statusCode'] ?? null,
                    'status_message' => $data['statusMessage'] ?? null,
                    'reference' => $data['reference'] ?? null,
                    'amount' => $data['amount'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => 'HTTP Error: ' . $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Duitku checkStatus error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify callback signature from Duitku
     */
    public function verifyCallback(string $merchantCode, string $amount, string $merchantOrderId, string $signature): bool
    {
        $expectedSignature = md5($merchantCode . $amount . $merchantOrderId . $this->apiKey);

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Parse status code to readable status
     */
    public function parseStatusCode(string $statusCode): string
    {
        return match ($statusCode) {
            '00' => 'paid',
            '01' => 'pending',
            '02' => 'failed',
            default => 'pending',
        };
    }
}
