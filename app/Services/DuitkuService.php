<?php

namespace App\Services;

use App\Models\PaymentOrder;
use App\Models\User;
use Duitku\Config as DuitkuConfig;
use Duitku\Pop as DuitkuPop;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DuitkuService
{
    private DuitkuConfig $config;

    private bool $isSandbox;

    public function __construct()
    {
        $apiKey = config('services.duitku.api_key');
        $merchantCode = config('services.duitku.merchant_code');
        $this->isSandbox = config('services.duitku.sandbox', true);

        $this->config = new DuitkuConfig($apiKey, $merchantCode);
        $this->config->setSandboxMode($this->isSandbox);
        $this->config->setSanitizedMode(true);
        $this->config->setDuitkuLogs(false);
    }

    /**
     * Create a Duitku invoice and return the payment URL.
     *
     * @param  array<string, mixed>  $params
     */
    public function createInvoice(User $user, array $params = []): PaymentOrder
    {
        $merchantOrderId = 'SPMB-'.strtoupper(Str::random(8)).'-'.time();
        $totalAmount = $params['amount'] ?? 250772;

        /** @var array<string, mixed> $customerDetail */
        $customerDetail = [
            'firstName' => $user->name,
            'lastName' => '',
            'email' => $user->email,
            'phoneNumber' => $user->phone ?? '',
            'billingAddress' => [
                'firstName' => $user->name,
                'lastName' => '',
                'address' => 'Indonesia',
                'city' => $user->birth_place ?? 'Jakarta',
                'postalCode' => '00000',
                'phone' => $user->phone ?? '',
                'countryCode' => 'ID',
            ],
        ];

        /** @var array<int, array<string, mixed>> $itemDetails */
        $itemDetails = [
            [
                'name' => 'Biaya Pendaftaran SPMB Hitech School',
                'price' => 250000,
                'quantity' => 1,
            ],
            [
                'name' => 'Kode Unik',
                'price' => 772,
                'quantity' => 1,
            ],
        ];

        $invoiceParams = [
            'paymentAmount' => $totalAmount,
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => 'Biaya Pendaftaran SPMB Hitech School',
            'additionalParam' => '',
            'merchantUserInfo' => $user->registration_number ?? '',
            'customerVaName' => $user->name,
            'email' => $user->email,
            'phoneNumber' => $user->phone ?? '',
            'itemDetails' => $itemDetails,
            'customerDetail' => $customerDetail,
            'callbackUrl' => route('payment.callback'),
            'returnUrl' => route('payment.return'),
            'expiryPeriod' => 60, // 60 minutes
        ];

        // Add selected payment method if specified
        if (! empty($params['paymentMethod'])) {
            $invoiceParams['paymentMethod'] = $params['paymentMethod'];
        }

        // Create the order record first
        $order = PaymentOrder::create([
            'user_id' => $user->id,
            'merchant_order_id' => $merchantOrderId,
            'payment_method' => $params['paymentMethod'] ?? null,
            'amount' => $totalAmount,
            'status' => 'pending',
        ]);

        try {
            $response = DuitkuPop::createInvoice($invoiceParams, $this->config);
            $result = json_decode($response, true);

            if (isset($result['paymentUrl'])) {
                $order->update(['reference' => $result['paymentUrl']]);
            } elseif (isset($result['reference'])) {
                $order->update(['reference' => $result['reference']]);
            }

            Log::info('Duitku invoice created', [
                'merchant_order_id' => $merchantOrderId,
                'user_id' => $user->id,
                'response' => $result,
            ]);
        } catch (Exception $e) {
            Log::error('Duitku invoice creation failed', [
                'merchant_order_id' => $merchantOrderId,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            $order->update(['status' => 'failed', 'notes' => $e->getMessage()]);

            throw $e;
        }

        return $order->fresh();
    }

    /**
     * Process the Duitku callback and update payment order status.
     *
     * @return array<string, mixed>
     */
    public function handleCallback(): array
    {
        $callback = DuitkuPop::callback($this->config);
        $notif = json_decode($callback, true);

        Log::info('Duitku callback received', $notif ?? []);

        $merchantOrderId = $notif['merchantOrderId'] ?? null;
        $resultCode = $notif['resultCode'] ?? null;
        $reference = $notif['reference'] ?? null;
        $paymentCode = $notif['paymentCode'] ?? null;

        if (! $merchantOrderId) {
            throw new Exception('Invalid callback: missing merchantOrderId');
        }

        $order = PaymentOrder::where('merchant_order_id', $merchantOrderId)->firstOrFail();

        if ($resultCode === '00') {
            $order->update([
                'status' => 'success',
                'result_code' => $resultCode,
                'payment_method' => $paymentCode ?? $order->payment_method,
                'notes' => "Reference: {$reference}",
            ]);

            // Mark user as paid
            $order->user->update([
                'payment_status' => 'paid',
                'payment_method' => $paymentCode ?? $order->payment_method,
            ]);

            Log::info('Payment successful', ['user_id' => $order->user_id, 'order' => $merchantOrderId]);
        } else {
            $order->update([
                'status' => 'failed',
                'result_code' => $resultCode,
                'notes' => "Payment failed with resultCode: {$resultCode}",
            ]);

            Log::warning('Payment failed', ['user_id' => $order->user_id, 'order' => $merchantOrderId]);
        }

        return $notif ?? [];
    }

    /**
     * Map UI payment method code to Duitku payment method code.
     */
    public static function mapPaymentMethod(string $uiMethod): string
    {
        return match (strtoupper($uiMethod)) {
            'MANDIRI' => 'M2',  // Mandiri VA
            'BNI' => 'B1',  // BNI VA
            'BCA' => 'BC',  // BCA VA
            'QRIS' => 'SP',  // ShopeePay / QRIS
            'GOPAY' => 'OV',  // GoPay / OVO
            default => '',    // Let Duitku show all methods
        };
    }
}
