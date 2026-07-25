<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrder;
use App\Services\DuitkuService;
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
        $signature = $request->query('signature');

        Log::info('Duitku return', [
            'merchantOrderId' => $merchantOrderId,
            'resultCode' => $resultCode,
            'reference' => $reference,
            'signature' => $signature,
        ]);

        // Find the payment order if merchantOrderId is provided
        $order = null;
        if ($merchantOrderId) {
            $order = PaymentOrder::where('merchant_order_id', $merchantOrderId)->first();
        }

        // Verify the signature from Duitku to ensure it's a valid redirect from Duitku
        $merchantCode = config('services.duitku.merchant_code');
        $apiKey = config('services.duitku.api_key');
        $expectedSignature = md5($merchantCode.$merchantOrderId.$resultCode.$apiKey);

        if (! $signature || $signature !== $expectedSignature) {
            Log::warning('Duitku return signature validation failed', [
                'received' => $signature,
                'expected' => $expectedSignature,
            ]);

            return redirect()->route('billing')
                ->with('payment_failed', true);
        }

        // A payment is successful if Duitku returns resultCode '00' or if our database already marked it as success
        $isSuccess = $resultCode === '00' || ($order && $order->status === 'success');

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
