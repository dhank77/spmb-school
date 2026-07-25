<div>
    {{-- Warning Banner --}}
    <div class="mb-8 bg-error-container text-on-error-container p-4 rounded-xl flex items-start gap-4">
        <span class="material-symbols-outlined flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 1;">warning</span>
        <p class="font-body-md text-body-md font-medium">
            Anda harus menyelesaikan pembayaran untuk dapat mengakses jadwal dan mengikuti ujian.
            Semua fitur lain dikunci hingga pembayaran selesai.
        </p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Invoice Main Section --}}
        <div class="flex-grow lg:w-2/3">
            <div class="invoice-card bg-surface-container-lowest rounded-2xl border border-outline-variant"
                 style="box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.05);">

                {{-- Invoice Header --}}
                <div class="p-6 md:p-8 border-b border-outline-variant">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                        <div>
                            <h1 class="font-headline-md text-headline-md text-on-surface mb-1">
                                Tagihan Pendaftaran - {{ Auth::user()->name }}
                            </h1>
                            <p class="font-label-md text-label-md text-on-surface-variant">
                                Nomor Tagihan:
                                <span class="font-bold text-on-surface">
                                    INV/SPMB/{{ date('Y') }}/{{ str_pad(Auth::id(), 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </p>
                            @if(Auth::user()->registration_number)
                            <p class="font-label-md text-label-md text-on-surface-variant mt-1">
                                No. Pendaftaran:
                                <span class="font-bold text-on-surface">{{ Auth::user()->registration_number }}</span>
                            </p>
                            @endif
                        </div>
                        <span class="bg-error text-on-error px-4 py-1.5 rounded-full font-label-sm text-label-sm tracking-wide uppercase font-bold flex-shrink-0">
                            BELUM BAYAR
                        </span>
                    </div>
                </div>

                {{-- Invoice Dates --}}
                <div class="p-6 md:p-8 border-b border-outline-variant">
                    <div class="grid grid-cols-2 gap-6 p-4 bg-surface-container-low rounded-xl border border-outline-variant">
                        <div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant mb-1 uppercase tracking-wider">Tanggal Terbit</p>
                            <p class="font-body-md text-body-md font-semibold">{{ now()->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant mb-1 uppercase tracking-wider">Jatuh Tempo</p>
                            <p class="font-body-md text-body-md font-semibold text-error">{{ now()->addDays(3)->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Billing Table --}}
                <div class="p-6 md:p-8 border-b border-outline-variant">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container text-on-surface-variant font-label-sm text-label-sm uppercase">
                                    <th class="py-3 px-4 rounded-l-lg tracking-wider">Deskripsi</th>
                                    <th class="py-3 px-4 text-right rounded-r-lg tracking-wider">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="font-body-md text-body-md">
                                <tr class="border-b border-surface-container">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-primary text-sm">school</span>
                                            </div>
                                            Biaya Pendaftaran
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-right font-medium">Rp {{ number_format($this->baseFee, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="border-b border-surface-container">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-secondary text-sm">tag</span>
                                            </div>
                                            Kode Unik
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-right font-medium">Rp {{ number_format($this->uniqueCode, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="bg-primary-container text-on-primary-container">
                                    <td class="py-4 px-4 rounded-l-xl font-bold">Total Pembayaran</td>
                                    <td class="py-4 px-4 text-right rounded-r-xl font-bold text-headline-sm">
                                        Rp {{ number_format($this->totalAmount(), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Payment Methods --}}
                <div class="p-6 md:p-8">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface mb-6">Pilih Metode Pembayaran</h2>

                    @error('selectedMethod')
                        <div class="mb-4 flex items-center gap-3 bg-error-container text-on-error-container px-4 py-3 rounded-xl text-sm font-medium">
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">error</span>
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Virtual Account --}}
                        <div class="p-4 rounded-xl border border-outline-variant bg-surface-container-lowest">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="material-symbols-outlined text-primary">account_balance</span>
                                <h3 class="font-label-md text-label-md font-bold text-primary uppercase">Virtual Account</h3>
                            </div>
                            <div class="space-y-3">
                                @foreach(['MANDIRI' => 'Bank Mandiri', 'BNI' => 'Bank BNI', 'BCA' => 'Bank BCA'] as $code => $label)
                                <button
                                    type="button"
                                    wire:click="selectMethod('{{ $code }}')"
                                    id="method-{{ $code }}"
                                    class="w-full flex items-center justify-between p-3 rounded-lg border transition-all duration-200 active:scale-[0.98]
                                        {{ $selectedMethod === $code
                                            ? 'border-primary bg-primary/5 ring-2 ring-primary/20'
                                            : 'border-outline-variant bg-white hover:border-primary cursor-pointer' }}"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-7 bg-surface-container rounded flex items-center justify-center font-bold text-[10px] text-on-surface-variant border border-outline-variant">
                                            {{ $code }}
                                        </div>
                                        <span class="font-body-md text-body-md">{{ $label }}</span>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                        {{ $selectedMethod === $code ? 'border-primary' : 'border-outline' }}">
                                        @if($selectedMethod === $code)
                                            <div class="w-2.5 h-2.5 rounded-full bg-primary"></div>
                                        @endif
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- E-Wallet & QRIS --}}
                        <div class="p-4 rounded-xl border border-outline-variant bg-surface-container-lowest">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="material-symbols-outlined text-primary">account_balance_wallet</span>
                                <h3 class="font-label-md text-label-md font-bold text-primary uppercase">E-Wallet &amp; QRIS</h3>
                            </div>
                            <div class="space-y-3">
                                @foreach(['QRIS' => 'QRIS', 'GOPAY' => 'GoPay'] as $code => $label)
                                <button
                                    type="button"
                                    wire:click="selectMethod('{{ $code }}')"
                                    id="method-{{ $code }}"
                                    class="w-full flex items-center justify-between p-3 rounded-lg border transition-all duration-200 active:scale-[0.98]
                                        {{ $selectedMethod === $code
                                            ? 'border-primary bg-primary/5 ring-2 ring-primary/20'
                                            : 'border-outline-variant bg-white hover:border-primary cursor-pointer' }}"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-7 bg-surface-container rounded flex items-center justify-center font-bold text-[10px] text-on-surface-variant border border-outline-variant">
                                            {{ $code }}
                                        </div>
                                        <span class="font-body-md text-body-md">{{ $label }}</span>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                        {{ $selectedMethod === $code ? 'border-primary' : 'border-outline' }}">
                                        @if($selectedMethod === $code)
                                            <div class="w-2.5 h-2.5 rounded-full bg-primary"></div>
                                        @endif
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:w-1/3">
            <div class="sticky top-24 space-y-6">

                {{-- Summary Card --}}
                <div class="bg-surface-container p-6 rounded-2xl border border-outline-variant"
                     style="box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.05);">
                    <h3 class="font-headline-sm text-headline-sm mb-6">Rangkuman Tagihan</h3>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between font-body-md">
                            <span class="text-on-surface-variant">Biaya Pendaftaran</span>
                            <span>Rp {{ number_format($this->baseFee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-body-md">
                            <span class="text-on-surface-variant">Kode Unik</span>
                            <span>Rp {{ number_format($this->uniqueCode, 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-3 border-t border-outline-variant flex justify-between font-bold text-primary">
                            <span>Total</span>
                            <span class="text-headline-sm">Rp {{ number_format($this->totalAmount(), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($selectedMethod)
                    <div class="mb-4 flex items-center gap-3 bg-secondary-container/30 text-on-secondary-container px-4 py-3 rounded-xl">
                        <span class="material-symbols-outlined text-sm text-secondary">check_circle</span>
                        <span class="font-label-md text-label-md">
                            Metode: <strong>{{ $paymentMethods[$selectedMethod]['label'] ?? $selectedMethod }}</strong>
                        </span>
                    </div>
                    @endif

                    <button
                        wire:click="payNow"
                        wire:loading.attr="disabled"
                        wire:target="payNow"
                        id="bayar-sekarang-btn"
                        class="w-full flex items-center justify-center gap-2 bg-secondary-container text-on-secondary-container py-4 rounded-xl font-bold text-body-lg hover:brightness-95 active:scale-95 transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="payNow" class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">payments</span>
                        <span wire:loading.remove wire:target="payNow">Bayar Sekarang</span>
                        <span wire:loading wire:target="payNow" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                    <p class="mt-4 text-center font-label-sm text-label-sm text-on-surface-variant px-2">
                        Dengan membayar, Anda menyetujui syarat dan ketentuan pendaftaran Hitech School.
                    </p>
                </div>

                {{-- Help Card --}}
                <div class="p-6 rounded-xl border border-dashed border-primary/40 bg-primary/5">
                    <div class="flex items-start gap-4">
                        <div class="p-2 bg-primary/10 rounded-full text-primary flex-shrink-0">
                            <span class="material-symbols-outlined">help_center</span>
                        </div>
                        <div>
                            <h4 class="font-label-md text-label-md font-bold text-on-surface mb-1">Butuh bantuan?</h4>
                            <p class="font-label-sm text-label-sm text-on-surface-variant mb-3">
                                Mengalami masalah saat pembayaran? Hubungi tim support kami.
                            </p>
                            <a href="https://wa.me/62882019679350" target="_blank"
                               class="font-label-sm text-label-sm text-primary font-bold hover:underline flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">chat</span>
                                Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
