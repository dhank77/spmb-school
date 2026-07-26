<x-layouts.student-portal :title="__('Dashboard')">

    {{-- Payment Success Flash --}}
    @if(session('payment_success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="mb-6 bg-secondary-container text-on-secondary-container p-4 rounded-xl flex items-center gap-4 animate-pulse"
         style="animation-duration: 2s; animation-iteration-count: 3;">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        <p class="font-body-md text-body-md font-medium">Pembayaran berhasil! Selamat datang di portal calon murid Hitech School.</p>
    </div>
    @endif

    {{-- Hero Welcome Section --}}
    <section class="mb-10 relative overflow-hidden rounded-3xl bg-primary px-6 py-10 md:px-16 text-on-primary shadow-lg">
        <div class="relative z-10 grid md:grid-cols-2 items-center gap-10">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-1 bg-on-primary-container text-primary rounded-full mb-4">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
                    <span class="font-label-sm text-label-sm">Akun Terverifikasi</span>
                </div>
                <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg mb-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
                <p class="font-body-lg text-body-lg text-primary-fixed opacity-90">
                    Nomor Pendaftaran: <span class="font-bold">#HTS-{{ date('Y') }}-{{ str_pad(Auth::id(), 4, '0', STR_PAD_LEFT) }}</span>
                </p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/20">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-label-md text-label-md">Kelengkapan Berkas</span>
                        <span class="font-label-md text-label-md">100%</span>
                    </div>
                    <div class="w-full h-2 bg-white/20 rounded-full overflow-hidden">
                        <div class="w-full h-full bg-tertiary-fixed rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Background shapes --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-secondary/20 rounded-full blur-3xl -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-tertiary/30 rounded-full blur-3xl -ml-20 -mb-20"></div>
    </section>

    {{-- Status & Quick Action Cards Row --}}
    <div class="grid md:grid-cols-3 gap-6 mb-10">
        {{-- Card 1: Status Pembayaran --}}
        <div class="p-6 bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-secondary-container/50 text-secondary rounded-xl">
                        <span class="material-symbols-outlined">receipt_long</span>
                    </div>
                    @if(Auth::user()->isPaid())
                        <span class="px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full text-xs font-bold">LUNAS</span>
                    @else
                        <span class="px-3 py-1 bg-error-container text-on-error-container rounded-full text-xs font-bold">BELUM BAYAR</span>
                    @endif
                </div>
                <h4 class="font-headline-sm text-headline-sm font-bold text-on-surface mb-1">Tagihan Pendaftaran</h4>
                <p class="text-on-surface-variant text-label-md">Biaya pendaftaran seleksi masuk SPMB {{ date('Y') }}.</p>
            </div>
            <div class="mt-6 pt-4 border-t border-outline-variant">
                <a href="{{ route('billing') }}" class="w-full inline-flex items-center justify-center gap-2 py-3 bg-secondary text-on-secondary font-bold rounded-xl hover:opacity-90 transition-all">
                    <span>Lihat Tagihan</span>
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
        </div>

        {{-- Card 2: Status Ujian Aktif --}}
        <div class="p-6 bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-primary/10 text-primary rounded-xl">
                        <span class="material-symbols-outlined">timer</span>
                    </div>
                    <span class="px-3 py-1 bg-primary-fixed/40 text-primary rounded-full text-xs font-bold">
                        {{ isset($activeSubjects) ? $activeSubjects->count() : 0 }} UJIAN READY
                    </span>
                </div>
                <h4 class="font-headline-sm text-headline-sm font-bold text-on-surface mb-1">Portal Ujian CBT</h4>
                <p class="text-on-surface-variant text-label-md">Ujian potensi akademik & tes penempatan bahasa.</p>
            </div>
            <div class="mt-6 pt-4 border-t border-outline-variant">
                <a href="{{ route('exam.active') }}" class="w-full inline-flex items-center justify-center gap-2 py-3 bg-primary text-on-primary font-bold rounded-xl hover:bg-primary-container transition-all">
                    <span>Buka Portal Ujian</span>
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
        </div>

        {{-- Card 3: Verifikasi Berkas --}}
        <div class="p-6 bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-tertiary-container/50 text-tertiary rounded-xl">
                        <span class="material-symbols-outlined">folder_managed</span>
                    </div>
                    <span class="px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full text-xs font-bold">VERIFIED</span>
                </div>
                <h4 class="font-headline-sm text-headline-sm font-bold text-on-surface mb-1">Status Berkas</h4>
                <p class="text-on-surface-variant text-label-md">Dokumen pendaftaran telah diverifikasi tim panitia.</p>
            </div>
            <div class="mt-6 pt-4 border-t border-outline-variant">
                <span class="text-label-md font-bold text-secondary flex items-center justify-center gap-1 py-3">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    Lengkap & Terverifikasi
                </span>
            </div>
        </div>
    </div>

    {{-- Dashboard Main Layout --}}
    <div class="grid lg:grid-cols-3 gap-6 lg:gap-10">

        {{-- Main Column (Left) --}}
        <div class="lg:col-span-2 flex flex-col gap-10">

            {{-- Alur Seleksi SPMB Section --}}
            <section>
                <h3 class="font-headline-md text-headline-md mb-6 flex items-center gap-4 text-primary">
                    <span class="material-symbols-outlined text-secondary">account_tree</span>
                    Alur Seleksi SPMB {{ date('Y') }}
                </h3>

                <div class="grid md:grid-cols-4 gap-4">
                    <div class="p-4 bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-sm">
                        <span class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container font-bold text-sm flex items-center justify-center mb-3">1</span>
                        <h5 class="font-label-md text-label-md font-bold mb-1">Registrasi</h5>
                        <p class="text-[11px] text-on-surface-variant">Mengisi form data diri calon siswa.</p>
                    </div>

                    <div class="p-4 bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-sm">
                        <span class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container font-bold text-sm flex items-center justify-center mb-3">2</span>
                        <h5 class="font-label-md text-label-md font-bold mb-1">Pembayaran</h5>
                        <p class="text-[11px] text-on-surface-variant">Membayar biaya administrasi seleksi.</p>
                    </div>

                    <div class="p-4 bg-primary/10 border border-primary/40 rounded-2xl shadow-sm">
                        <span class="w-8 h-8 rounded-full bg-primary text-on-primary font-bold text-sm flex items-center justify-center mb-3">3</span>
                        <h5 class="font-label-md text-label-md font-bold text-primary mb-1">Ujian CBT</h5>
                        <p class="text-[11px] text-on-surface-variant">Mengerjakan tes potensi akademik online.</p>
                    </div>

                    <div class="p-4 bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-sm opacity-75">
                        <span class="w-8 h-8 rounded-full bg-surface-container-high text-on-surface-variant font-bold text-sm flex items-center justify-center mb-3">4</span>
                        <h5 class="font-label-md text-label-md font-bold mb-1">Pengumuman</h5>
                        <p class="text-[11px] text-on-surface-variant">Melihat kelolosan & daftar ulang.</p>
                    </div>
                </div>
            </section>

            {{-- Jadwal Mendatang Summary Section --}}
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-headline-md text-headline-md flex items-center gap-4 text-primary">
                        <span class="material-symbols-outlined text-secondary">calendar_month</span>
                        Jadwal Ujian Mendatang
                    </h3>
                    <a href="{{ route('exam.active') }}" class="font-label-sm text-label-sm text-primary font-bold hover:underline">
                        Lihat Semua Jadwal →
                    </a>
                </div>

                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-surface-container-low">
                            <tr>
                                <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Mata Ujian</th>
                                <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider hidden sm:table-cell">Tanggal</th>
                                <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider hidden md:table-cell">Ruangan</th>
                                <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Sesi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container">
                            @forelse($upcomingExams ?? [] as $exam)
                            <tr class="hover:bg-surface-container transition-colors">
                                <td class="px-6 py-6 font-label-md text-label-md font-bold">
                                    {{ $exam->name }}
                                </td>
                                <td class="px-6 py-6 text-on-surface-variant hidden sm:table-cell">
                                    {{ \Carbon\Carbon::parse($exam->date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-6 text-on-surface-variant hidden md:table-cell">
                                    {{ $exam->room }}
                                </td>
                                <td class="px-6 py-6">
                                    <span class="px-4 py-1 bg-secondary-container text-on-secondary-container rounded-full text-xs font-bold">
                                        {{ $exam->session }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-on-surface-variant font-body-md text-body-md">
                                    Belum ada jadwal ujian mendatang.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </div>

        {{-- Sidebar Column (Right) --}}
        <div class="flex flex-col gap-10">

            {{-- Statistics Bento Card --}}
            <div class="bg-secondary p-6 rounded-3xl text-on-secondary shadow-lg">
                <p class="font-label-sm text-label-sm opacity-80 mb-1">Status Pendaftaran</p>
                <h4 class="font-headline-md text-headline-md mb-6">Tahap Ujian Seleksi</h4>

                <div class="relative h-32 flex items-center justify-center">
                    <svg class="w-24 h-24 transform -rotate-90">
                        <circle class="opacity-20" cx="48" cy="48" fill="transparent" r="40" stroke="currentColor" stroke-width="8"></circle>
                        <circle cx="48" cy="48" fill="transparent" r="40" stroke="#BBDC12" stroke-dasharray="251.2" stroke-dashoffset="62.8" stroke-width="8" stroke-linecap="round"></circle>
                    </svg>
                    <span class="absolute font-display-lg text-headline-md">75%</span>
                </div>
                <p class="text-center text-label-sm mt-6 opacity-90 italic">"Selangkah lagi menuju Hitech School!"</p>
            </div>

            {{-- Support Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant p-6 rounded-3xl shadow-sm">
                <h4 class="font-label-md text-label-md font-bold mb-2">Bantuan Pendaftaran</h4>
                <p class="text-on-surface-variant text-label-md mb-6">Tim panitia SPMB siap membantu Anda jika mengalami kendala pendaftaran.</p>

                <a href="https://wa.me/62882019679350" target="_blank" class="w-full flex justify-center bg-[#BBDC12] text-primary py-4 rounded-xl font-bold transition-transform hover:scale-[1.02] active:scale-95 text-center">
                    Hubungi Panitia SPMB
                </a>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hour = new Date().getHours();
            const welcomeText = document.querySelector('h1.font-display-lg');
            if(welcomeText && welcomeText.innerHTML.includes('Selamat Datang')) {
                let greeting = "Selamat Datang";
                if(hour >= 4 && hour < 11) greeting = "Selamat Pagi";
                else if(hour >= 11 && hour < 15) greeting = "Selamat Siang";
                else if(hour >= 15 && hour < 19) greeting = "Selamat Sore";
                else greeting = "Selamat Malam";

                welcomeText.innerHTML = welcomeText.innerHTML.replace('Selamat Datang', greeting);
            }
        });
    </script>
    @endpush
</x-layouts.student-portal>
