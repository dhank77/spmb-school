<?php

namespace App\Livewire\Admission;

use App\Models\AdmissionWave;
use App\Models\PaymentOrder;
use App\Services\DuitkuService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.student-portal')]
#[Title('Tagihan Pembayaran - Portal Calon Murid')]
class Billing extends Component
{
    public string $selectedMethod = '';

    public bool $processingError = false;

    public string $processingErrorMessage = '';

    /** @var array<string, array{label: string, code: string, group: string}> */
    public array $paymentMethods = [
        'MANDIRI' => ['label' => 'Bank Mandiri', 'code' => 'MANDIRI', 'group' => 'Virtual Account'],
        'BNI' => ['label' => 'Bank BNI', 'code' => 'BNI', 'group' => 'Virtual Account'],
        'BCA' => ['label' => 'Bank BCA', 'code' => 'BCA', 'group' => 'Virtual Account'],
        'QRIS' => ['label' => 'QRIS', 'code' => 'QRIS', 'group' => 'E-Wallet & QRIS'],
        'GOPAY' => ['label' => 'GoPay', 'code' => 'GOPAY', 'group' => 'E-Wallet & QRIS'],
    ];

    public int $baseFee = 250000;

    public int $uniqueCode = 0;

    public function mount(): void
    {
        $user = Auth::user();
        if ($user && $user->isPaid()) {
            $successfulOrder = PaymentOrder::where('user_id', $user->id)
                ->where('status', 'success')
                ->latest()
                ->first();

            if ($successfulOrder) {
                $amount = $successfulOrder->amount;
                $rem10000 = $amount % 10000;
                if ($rem10000 >= 4000 && $rem10000 <= 4999) {
                    $this->uniqueCode = $rem10000;
                } else {
                    $this->uniqueCode = $amount % 1000;
                }
                $this->baseFee = $amount - $this->uniqueCode;

                return;
            }
        }

        $activeWave = AdmissionWave::where('status', 'active')->first();
        if ($activeWave) {
            $this->baseFee = $activeWave->registration_cost;
        }

        $this->uniqueCode = rand(4000, 4999);
    }

    public function selectMethod(string $method): void
    {
        if (array_key_exists($method, $this->paymentMethods)) {
            $this->selectedMethod = $method;
            $this->processingError = false;
        }
    }

    public function payNow(DuitkuService $duitku): void
    {
        $this->processingError = false;

        $this->validate([
            'selectedMethod' => ['required', 'string', 'in:MANDIRI,BNI,BCA,QRIS,GOPAY'],
        ], [
            'selectedMethod.required' => 'Silakan pilih metode pembayaran terlebih dahulu.',
        ]);

        $user = Auth::user();

        // Prevent duplicate pending orders — check if there's an existing pending order
        $existingOrder = PaymentOrder::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($existingOrder && $existingOrder->reference) {
            // Redirect to existing payment URL
            $this->redirect($existingOrder->reference);

            return;
        }

        try {
            $duitkuMethod = DuitkuService::mapPaymentMethod($this->selectedMethod);

            $order = $duitku->createInvoice($user, [
                'amount' => $this->totalAmount(),
                'paymentMethod' => $duitkuMethod,
                'baseFee' => $this->baseFee,
                'uniqueCode' => $this->uniqueCode,
            ]);

            if ($order->reference) {
                $this->redirect($order->reference);
            } else {
                $this->processingError = true;
                $this->processingErrorMessage = 'Gagal mendapatkan halaman pembayaran. Silakan coba lagi.';
            }
        } catch (Exception $e) {
            Log::error('Billing::payNow error', ['error' => $e->getMessage(), 'user_id' => $user->id]);

            $this->processingError = true;
            $this->processingErrorMessage = 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.';
        }
    }

    public function totalAmount(): int
    {
        return $this->baseFee + $this->uniqueCode;
    }

    public function render(): View
    {
        return view('livewire.admission.billing');
    }
}
