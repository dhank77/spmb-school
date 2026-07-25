<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrder;
use App\Services\DuitkuService;
use Duitku\Config;
use Duitku\Pop;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private readonly DuitkuService $duitku) {}

    /**
     * Handle Duitku payment callback (webhook from Duitku server).
     * This endpoint must be excluded from CSRF verification.
     */
    public function callback(Request $request): Response
    {
        try {
            $this->duitku->handleCallback();

            return response('OK', 200);
        } catch (Exception $e) {
            Log::error('Duitku callback error', ['error' => $e->getMessage()]);

            return response($e->getMessage(), 400);
        }
    }

    /**
     * Handle user return after Duitku payment flow.
     * Duitku redirects the user here after payment (success, pending, or cancel).
     */
    public function return(Request $request): RedirectResponse
    {
        $merchantOrderId = $request->query('merchantOrderId');
        $resultCode = $request->query('resultCode');
        $reference = $request->query('reference');

        Log::info('Duitku return', [
            'merchantOrderId' => $merchantOrderId,
            'resultCode' => $resultCode,
            'reference' => $reference,
        ]);

        // Find the payment order if merchantOrderId is provided
        $order = null;
        if ($merchantOrderId) {
            $order = PaymentOrder::where('merchant_order_id', $merchantOrderId)->first();
        }

        // Determine if the payment was successful
        $isSuccess = false;

        // 1. If our database already marked it as success via callback
        if ($order && $order->status === 'success') {
            $isSuccess = true;
        }
        // 2. If it is resultCode '00' but the webhook callback hasn't arrived yet,
        // we verify the status directly against Duitku's API for security (preventing URL manipulation).
        elseif ($resultCode === '00' && $merchantOrderId) {
            try {
                // Fetch credentials
                $apiKey = config('services.duitku.api_key');
                $merchantCode = config('services.duitku.merchant_code');
                $isSandbox = config('services.duitku.sandbox', true);

                $duitkuConfig = new Config($apiKey, $merchantCode);
                $duitkuConfig->setSandboxMode($isSandbox);

                // Call Duitku status checker API
                $statusResponse = Pop::transactionStatus($merchantOrderId, $duitkuConfig);
                $transaction = json_decode($statusResponse);

                if ($transaction && isset($transaction->statusCode) && $transaction->statusCode === '00') {
                    $isSuccess = true;

                    // Sync the order status in database
                    if ($order) {
                        $order->update([
                            'status' => 'success',
                            'result_code' => '00',
                            'notes' => 'Verified via redirect return API check',
                        ]);
                    }
                }
            } catch (Exception $e) {
                Log::error('Duitku API status check failed on return', ['error' => $e->getMessage()]);
            }
        }

        if ($isSuccess) {
            // Update the user's status to paid as a safety net if it hasn't been done by the callback yet
            if (auth()->check()) {
                $user = auth()->user();
                if (! $user->isPaid()) {
                    $user->update([
                        'payment_status' => 'paid',
                        'payment_method' => $order?->payment_method ?? $user->payment_method,
                    ]);
                }
            } elseif ($order) {
                // If user is not logged in but we found the order, update the order's user directly
                $order->user->update([
                    'payment_status' => 'paid',
                    'payment_method' => $order->payment_method,
                ]);
            }

            return redirect()->route('dashboard')
                ->with('payment_success', true);
        }

        if ($resultCode === '01' || ($order && $order->status === 'pending')) {
            return redirect()->route('billing')
                ->with('payment_pending', true);
        }

        // Failed or cancelled
        return redirect()->route('billing')
            ->with('payment_failed', true);
    }
}
