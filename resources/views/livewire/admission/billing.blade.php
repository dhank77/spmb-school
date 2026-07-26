<div>
    {{-- Payment Status Flash Messages --}}
    @if(session('payment_pending'))
    <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded-xl flex items-center gap-4">
        <span class="material-symbols-outlined flex-shrink-0" style="font-variation-settings: 'FILL' 1;">pending</span>
        <p class="font-body-md text-body-md font-medium">Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi dari bank/e-wallet.</p>
    </div>
    @endif

    @if(session('payment_failed'))
    <div class="mb-6 bg-error-container text-on-error-container p-4 rounded-xl flex items-center gap-4">
        <span class="material-symbols-outlined flex-shrink-0" style="font-variation-settings: 'FILL' 1;">error</span>
        <p class="font-body-md text-body-md font-medium">Pembayaran dibatalkan atau gagal. Silakan pilih metode dan coba lagi.</p>
    </div>
    @endif

    {{-- Warning / Success Banner --}}
    @if(Auth::user()->isPaid())
    <div class="mb-8 p-4 rounded-xl flex items-start gap-4 border" style="background-color: #e8f5e9; color: #1b5e20; border-color: #c8e6c9;">
        <span class="material-symbols-outlined flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 1; color: #2e7d32;">check_circle</span>
        <p class="font-body-md text-body-md font-medium">
            Pembayaran Anda telah berhasil diverifikasi. Anda sekarang memiliki akses penuh ke portal calon murid.
        </p>
    </div>
    @else
    <div class="mb-8 bg-error-container text-on-error-container p-4 rounded-xl flex items-start gap-4">
        <span class="material-symbols-outlined flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 1;">warning</span>
        <p class="font-body-md text-body-md font-medium">
            Anda harus menyelesaikan pembayaran untuk dapat mengakses jadwal dan mengikuti ujian.
            Semua fitur lain dikunci hingga pembayaran selesai.
        </p>
    </div>
    @endif

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
                        @if(Auth::user()->isPaid())
                        <span class="bg-success text-white px-4 py-1.5 rounded-full font-label-sm text-label-sm tracking-wide uppercase font-bold flex-shrink-0" style="background-color: #2e7d32;">
                            LUNAS
                        </span>
                        @else
                        <span class="bg-error text-on-error px-4 py-1.5 rounded-full font-label-sm text-label-sm tracking-wide uppercase font-bold flex-shrink-0">
                            BELUM BAYAR
                        </span>
                        @endif
                    </div>

                    {{-- Export Buttons --}}
                    <div class="flex items-center gap-3 mt-5 pt-4 border-t border-outline-variant">
                        <span class="font-label-sm text-label-sm text-on-surface-variant">Unduh tagihan:</span>
                        <a href="{{ route('billing.export.pdf') }}"
                           target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant bg-surface-container text-on-surface font-label-sm text-label-sm font-medium hover:bg-error-container hover:text-on-error-container hover:border-error transition-all duration-150 active:scale-95"
                        >
                            <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                            PDF
                        </a>
                        <a href="{{ route('billing.export.excel.preview') }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant bg-surface-container text-on-surface font-label-sm text-label-sm font-medium hover:bg-secondary-container hover:text-on-secondary-container hover:border-secondary transition-all duration-150 active:scale-95"
                        >
                            <span class="material-symbols-outlined text-[16px]">table_view</span>
                            Excel
                        </a>
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
                <div class="p-6 md:p-8">
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
                                @if(Auth::user()->isPaid())
                                <tr class="bg-success-container text-on-success-container" style="background-color: #e8f5e9; color: #1b5e20;">
                                    <td class="py-4 px-4 rounded-l-xl font-bold">Status Pembayaran</td>
                                    <td class="py-4 px-4 text-right rounded-r-xl font-bold text-headline-sm">
                                        LUNAS ({{ Auth::user()->payment_method }})
                                    </td>
                                </tr>
                                @else
                                <tr class="bg-primary-container text-on-primary-container">
                                    <td class="py-4 px-4 rounded-l-xl font-bold">Total Pembayaran</td>
                                    <td class="py-4 px-4 text-right rounded-r-xl font-bold text-headline-sm">
                                        Rp {{ number_format($this->totalAmount(), 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:w-1/3">
            <div class="sticky top-24 space-y-6">

                {{-- Payment Methods Card (Moved above Summary Card) --}}
                @if(!Auth::user()->isPaid())
                <div x-data="{ vaOpen: true, qrisOpen: true }" class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm transition-all duration-200">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface mb-6">Pilih Metode Pembayaran</h2>

                    @error('selectedMethod')
                        <div class="mb-4 flex items-center gap-3 bg-error-container text-on-error-container px-4 py-3 rounded-xl text-sm font-medium">
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">error</span>
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="space-y-4">
                        {{-- Virtual Account --}}
                        <div>
                            <button @click="vaOpen = !vaOpen" type="button" class="w-full flex items-center justify-between mb-3 text-left focus:outline-none select-none cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-[20px]">account_balance</span>
                                    <h3 class="font-label-md text-label-md font-bold text-primary uppercase">Virtual Account</h3>
                                </div>
                                <span class="material-symbols-outlined transition-transform duration-200 text-on-surface-variant" :class="vaOpen ? 'rotate-180' : ''">expand_more</span>
                            </button>
                            <div x-show="vaOpen"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="space-y-2"
                            >
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
                                        <span class="font-body-md text-body-md text-left">{{ $label }}</span>
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
                        <div class="pt-3 border-t border-outline-variant">
                            <button @click="qrisOpen = !qrisOpen" type="button" class="w-full flex items-center justify-between mb-3 text-left focus:outline-none select-none cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-[20px]">account_balance_wallet</span>
                                    <h3 class="font-label-md text-label-md font-bold text-primary uppercase">E-Wallet &amp; QRIS</h3>
                                </div>
                                <span class="material-symbols-outlined transition-transform duration-200 text-on-surface-variant" :class="qrisOpen ? 'rotate-180' : ''">expand_more</span>
                            </button>
                            <div x-show="qrisOpen"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="space-y-2"
                            >
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
                                        <span class="font-body-md text-body-md text-left">{{ $label }}</span>
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
                @endif

                {{-- Summary Card --}}
                <div class="bg-surface-container p-6 rounded-2xl border border-outline-variant"
                     style="box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.05);">
                    <h3 class="font-headline-sm text-headline-sm mb-6">Rangkuman {{ Auth::user()->isPaid() ? 'Pembayaran' : 'Tagihan' }}</h3>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between font-body-md">
                            <span class="text-on-surface-variant">Biaya Pendaftaran</span>
                            <span>Rp {{ number_format($this->baseFee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-body-md">
                            <span class="text-on-surface-variant">Kode Unik & Fee</span>
                            <span>Rp {{ number_format($this->uniqueCode, 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-3 border-t border-outline-variant flex justify-between font-bold text-primary">
                            <span>Total</span>
                            <span class="text-headline-sm">Rp {{ number_format($this->totalAmount(), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if(Auth::user()->isPaid())
                    <div class="mb-4 flex items-center gap-3 bg-success-container text-on-success-container px-4 py-3 rounded-xl" style="background-color: #e8f5e9; color: #1b5e20;">
                        <span class="material-symbols-outlined text-sm text-success" style="color: #2e7d32;">check_circle</span>
                        <span class="font-label-md text-label-md">
                            Pembayaran Lunas menggunakan <strong>{{ Auth::user()->payment_method }}</strong>
                        </span>
                    </div>

                    <a
                        href="{{ route('dashboard') }}"
                        class="w-full flex items-center justify-center gap-2 bg-primary text-on-primary py-4 rounded-xl font-bold text-body-lg hover:brightness-95 active:scale-95 transition-all shadow-md text-center"
                        style="color: white; background-color: var(--color-primary, #6750a4);"
                    >
                        <span class="material-symbols-outlined">dashboard</span>
                        <span>Ke Dashboard Utama</span>
                    </a>
                    @else
                        @if($selectedMethod)
                        <div class="mb-4 flex items-center gap-3 bg-secondary-container/30 text-on-secondary-container px-4 py-3 rounded-xl">
                            <span class="material-symbols-outlined text-sm text-secondary">check_circle</span>
                            <span class="font-label-md text-label-md">
                                Metode: <strong>{{ $paymentMethods[$selectedMethod]['label'] ?? $selectedMethod }}</strong>
                            </span>
                        </div>
                        @endif

                        @if($processingError)
                        <div class="mb-4 flex items-start gap-3 bg-error-container text-on-error-container px-4 py-3 rounded-xl">
                            <span class="material-symbols-outlined text-sm flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 1;">error</span>
                            <span class="font-label-md text-label-md">{{ $processingErrorMessage }}</span>
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
                            <span wire:loading.remove wire:target="payNow">Lanjut ke Halaman Pembayaran</span>
                            <span wire:loading wire:target="payNow" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menghubungkan ke Duitku...
                            </span>
                        </button>
                        <p class="mt-4 text-center font-label-sm text-label-sm text-on-surface-variant px-2">
                            Anda akan diarahkan ke halaman pembayaran Duitku yang aman.
                        </p>
                    @endif
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
