<x-layouts.student-portal>
    @php
        $invNumber = 'INV/SPMB/' . date('Y') . '/' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
        $total = $baseFee + $uniqueCode;

        $rows = [
            ['Nomor Tagihan',      $invNumber],
            ['Nama Pendaftar',     $user->name],
            ['Email',              $user->email],
            ['No. Pendaftaran',    $user->registration_number ?? '-'],
            ['Tanggal Terbit',     now()->format('d/m/Y')],
            ['Status',             $isPaid ? 'LUNAS' : 'BELUM BAYAR'],
            ['Metode Pembayaran',  $isPaid ? ($user->payment_method ?? '-') : '-'],
            null, // separator
            ['Biaya Pendaftaran',  'Rp ' . number_format($baseFee, 0, ',', '.')],
            ['Kode Unik & Fee',    'Rp ' . number_format($uniqueCode, 0, ',', '.')],
            ['Total ' . ($isPaid ? 'Dibayarkan' : 'Pembayaran'), 'Rp ' . number_format($total, 0, ',', '.')],
        ];
    @endphp

    <div class="max-w-3xl mx-auto">

        {{-- Page Header --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('billing') }}" class="p-2 rounded-lg hover:bg-surface-container transition-colors text-on-surface-variant">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="font-headline-md text-headline-md text-on-surface">Preview Export Excel</h1>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Periksa data sebelum mengunduh file spreadsheet</p>
            </div>
        </div>

        {{-- Preview Card --}}
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden mb-6">

            {{-- Card Header --}}
            <div class="p-6 border-b border-outline-variant flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-secondary-container rounded-xl">
                        <span class="material-symbols-outlined text-on-secondary-container" style="font-variation-settings: 'FILL' 1;">table_view</span>
                    </div>
                    <div>
                        <p class="font-title-md text-on-surface font-semibold">Tagihan Pendaftaran</p>
                        <p class="font-label-sm text-on-surface-variant">{{ $invNumber }}</p>
                    </div>
                </div>
                @if($isPaid)
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider" style="background-color: #2e7d32; color: #fff;">LUNAS</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-error text-on-error">BELUM BAYAR</span>
                @endif
            </div>

            {{-- Table Preview --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr style="background-color: #6750a4;">
                            <th class="py-3 px-5 font-label-sm text-label-sm uppercase tracking-wider font-bold" style="color: #fff;">Keterangan</th>
                            <th class="py-3 px-5 text-right font-label-sm text-label-sm uppercase tracking-wider font-bold" style="color: #fff;">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @foreach($rows as $row)
                            @if($row === null)
                                <tr class="bg-surface-container-low"><td colspan="2" class="py-1"></td></tr>
                            @else
                                @php $isTotal = str_starts_with($row[0], 'Total') @endphp
                                <tr style="{{ $isTotal ? 'background-color: #eaddff;' : '' }}">
                                    <td class="py-3 px-5 font-label-md text-label-md font-semibold text-on-surface {{ $isTotal ? 'font-bold' : '' }}">{{ $row[0] }}</td>
                                    <td class="py-3 px-5 text-right font-body-md text-body-md text-on-surface {{ $isTotal ? 'font-bold' : '' }}">{{ $row[1] }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Info note --}}
        <div class="flex items-start gap-3 p-4 rounded-xl border border-outline-variant bg-surface-container mb-6 text-on-surface-variant">
            <span class="material-symbols-outlined text-[18px] flex-shrink-0 mt-0.5">info</span>
            <p class="font-label-sm text-label-sm">
                File Excel yang diunduh akan memiliki tampilan yang sama seperti tabel di atas, lengkap dengan warna header dan baris total.
                Format file: <strong>.xlsx</strong>
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('billing') }}"
               class="flex items-center gap-2 px-5 py-2.5 rounded-xl border border-outline-variant text-on-surface font-label-md font-medium hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
            <a href="{{ route('billing.export.excel') }}"
               class="flex items-center gap-2 px-6 py-2.5 rounded-xl font-label-md font-bold text-on-secondary-container bg-secondary-container hover:brightness-95 active:scale-95 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">download</span>
                Unduh Excel (.xlsx)
            </a>
        </div>

    </div>
</x-layouts.student-portal>
