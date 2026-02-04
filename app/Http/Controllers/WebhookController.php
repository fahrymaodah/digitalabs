<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\DuitkuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected DuitkuService $duitkuService;

    public function __construct(DuitkuService $duitkuService)
    {
        $this->duitkuService = $duitkuService;
    }

    /**
     * Handle Duitku payment callback/webhook
     */
    public function duitku(Request $request)
    {
        Log::info('Duitku webhook received', ['data' => $request->all()]);

        // Get callback data
        $merchantCode = $request->input('merchantCode');
        $amount = $request->input('amount');
        $merchantOrderId = $request->input('merchantOrderId');
        $productDetail = $request->input('productDetail');
        $additionalParam = $request->input('additionalParam');
        $paymentCode = $request->input('paymentCode');
        $resultCode = $request->input('resultCode');
        $merchantUserId = $request->input('merchantUserId');
        $reference = $request->input('reference');
        $signature = $request->input('signature');
        $publisherOrderId = $request->input('publisherOrderId');
        $spUserHash = $request->input('spUserHash');
        $settlementDate = $request->input('settlementDate');
        $issuerCode = $request->input('issuerCode');

        // Verify signature
        if (!$this->duitkuService->verifyCallback($merchantCode, $amount, $merchantOrderId, $signature)) {
            Log::warning('Duitku webhook: Invalid signature', [
                'merchantOrderId' => $merchantOrderId,
                'signature' => $signature,
            ]);

            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        // Find order
        $order = Order::where('order_number', $merchantOrderId)->first();

        if (!$order) {
            Log::warning('Duitku webhook: Order not found', ['merchantOrderId' => $merchantOrderId]);

            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        // Skip if already processed
        if ($order->status === 'paid') {
            Log::info('Duitku webhook: Order already paid', ['merchantOrderId' => $merchantOrderId]);

            return response()->json(['status' => 'ok', 'message' => 'Already processed']);
        }

        // Parse result code
        // 00 = Success
        // 01 = Pending
        // 02 = Failed/Cancelled
        $newStatus = $this->duitkuService->parseStatusCode($resultCode);

        Log::info('Duitku webhook: Processing', [
            'merchantOrderId' => $merchantOrderId,
            'resultCode' => $resultCode,
            'newStatus' => $newStatus,
        ]);

        if ($newStatus === 'paid') {
            // Payment successful - use model's markAsPaid() method
            // This will:
            // 1. Update order status to 'paid'
            // 2. Grant course access to user (create UserCourse)
            // 3. Create affiliate commission if applicable
            $order->markAsPaid();

            // Update additional Duitku data
            $order->update([
                'duitku_reference' => $reference,
                'duitku_response' => array_merge($order->duitku_response ?? [], [
                    'callback' => $request->all(),
                    'callback_at' => now()->toIso8601String(),
                ]),
            ]);

            Log::info('Duitku webhook: Payment successful', [
                'merchantOrderId' => $merchantOrderId,
                'reference' => $reference,
            ]);

            // TODO: Send email notification to user
            // event(new OrderPaid($order));

        } elseif ($newStatus === 'failed') {
            $order->update([
                'status' => 'failed',
                'duitku_response' => array_merge($order->duitku_response ?? [], [
                    'callback' => $request->all(),
                    'callback_at' => now()->toIso8601String(),
                ]),
            ]);

            Log::info('Duitku webhook: Payment failed', ['merchantOrderId' => $merchantOrderId]);
        }

        // Return success response to Duitku
        return response()->json(['status' => 'ok']);
    }
}
