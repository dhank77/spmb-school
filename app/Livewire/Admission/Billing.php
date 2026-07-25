<?php

namespace App\Livewire\Admission;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.student-portal')]
#[Title('Tagihan Pembayaran - Portal Calon Murid')]
class Billing extends Component
{
    public string $selectedMethod = '';

    /** @var array<string, array{label: string, code: string, group: string}> */
    public array $paymentMethods = [
        'MANDIRI' => ['label' => 'Bank Mandiri', 'code' => 'MANDIRI', 'group' => 'Virtual Account'],
        'BNI' => ['label' => 'Bank BNI', 'code' => 'BNI', 'group' => 'Virtual Account'],
        'BCA' => ['label' => 'Bank BCA', 'code' => 'BCA', 'group' => 'Virtual Account'],
        'QRIS' => ['label' => 'QRIS', 'code' => 'QRIS', 'group' => 'E-Wallet & QRIS'],
        'GOPAY' => ['label' => 'GoPay', 'code' => 'GOPAY', 'group' => 'E-Wallet & QRIS'],
    ];

    public int $baseFee = 250000;

    public int $uniqueCode = 772;

    public function selectMethod(string $method): void
    {
        if (array_key_exists($method, $this->paymentMethods)) {
            $this->selectedMethod = $method;
        }
    }

    public function payNow(): void
    {
        $this->validate([
            'selectedMethod' => ['required', 'string', 'in:MANDIRI,BNI,BCA,QRIS,GOPAY'],
        ], [
            'selectedMethod.required' => 'Silakan pilih metode pembayaran terlebih dahulu.',
        ]);

        $user = Auth::user();
        $user->update([
            'payment_status' => 'paid',
            'payment_method' => $this->selectedMethod,
        ]);

        session()->flash('payment_success', true);

        $this->redirectRoute('dashboard', navigate: true);
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
