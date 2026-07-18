<x-layouts.student-portal :title="__('Dashboard')">
    
    <!-- Hero Welcome Section -->
    <section class="mb-16 relative overflow-hidden rounded-3xl bg-primary px-6 py-10 md:px-16 text-on-primary shadow-lg">
        <div class="relative z-10 grid md:grid-cols-2 items-center gap-10">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-1 bg-on-primary-container text-primary rounded-full mb-4">
                    <span class="material-symbols-outlined text-sm" data-icon="verified" style="font-variation-settings: 'FILL' 1;">verified</span>
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
        
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-secondary/20 rounded-full blur-3xl -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-tertiary/30 rounded-full blur-3xl -ml-20 -mb-20"></div>
    </section>

    <div class="grid lg:grid-cols-3 gap-6 lg:gap-10">
        <!-- Main Grid Left Column (Content) -->
        <div class="lg:col-span-2 flex flex-col gap-10">
            
            <!-- Ujian Aktif Section -->
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-headline-md text-headline-md flex items-center gap-4 text-primary">
                        <span class="material-symbols-outlined text-secondary" data-icon="timer">timer</span>
                        Ujian Aktif
                    </h3>
                    <span class="px-4 py-1 bg-error-container text-on-error-container rounded-full font-label-sm text-label-sm">2 Ujian Berlangsung</span>
                </div>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Exam Card 1 -->
                    <div class="glass-card exam-card-hover border border-outline-variant p-6 rounded-2xl flex flex-col h-full border-t-4 border-t-primary bg-white">
                        <div class="flex justify-between items-start mb-6">
                            <div class="p-2 bg-primary/10 rounded-lg">
                                <span class="material-symbols-outlined text-primary" data-icon="psychology">psychology</span>
                            </div>
                            <span class="font-label-sm text-label-sm text-secondary font-bold">Wajib</span>
                        </div>
                        <h4 class="font-headline-sm text-headline-sm mb-2">Tes Potensi Akademik</h4>
                        <p class="text-on-surface-variant text-label-md mb-6">Mengukur kemampuan logika, numerik, dan verbal calon siswa.</p>
                        
                        <div class="mt-auto space-y-4">
                            <div class="flex items-center gap-6 text-on-surface-variant text-label-md">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm" data-icon="schedule">schedule</span>
                                    <span>90 Menit</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm" data-icon="description">description</span>
                                    <span>50 Soal</span>
                                </div>
                            </div>
                            <button class="w-full bg-primary text-on-primary py-4 rounded-xl font-headline-sm text-headline-sm transition-all hover:bg-primary-container">Mulai Ujian</button>
                        </div>
                    </div>
                    
                    <!-- Exam Card 2 -->
                    <div class="glass-card exam-card-hover border border-outline-variant p-6 rounded-2xl flex flex-col h-full border-t-4 border-t-tertiary bg-white">
                        <div class="flex justify-between items-start mb-6">
                            <div class="p-2 bg-tertiary/10 rounded-lg">
                                <span class="material-symbols-outlined text-tertiary" data-icon="translate">translate</span>
                            </div>
                            <span class="font-label-sm text-label-sm text-tertiary font-bold">Penempatan</span>
                        </div>
                        <h4 class="font-headline-sm text-headline-sm mb-2">Tes Bahasa Inggris</h4>
                        <p class="text-on-surface-variant text-label-md mb-6">Uji kompetensi bahasa untuk penempatan kelas bilingual.</p>
                        
                        <div class="mt-auto space-y-4">
                            <div class="flex items-center gap-6 text-on-surface-variant text-label-md">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm" data-icon="schedule">schedule</span>
                                    <span>60 Menit</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm" data-icon="description">description</span>
                                    <span>40 Soal</span>
                                </div>
                            </div>
                            <button class="w-full bg-primary text-on-primary py-4 rounded-xl font-headline-sm text-headline-sm transition-all hover:bg-primary-container">Mulai Ujian</button>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Jadwal Mendatang Section -->
            <section>
                <h3 class="font-headline-md text-headline-md mb-6 flex items-center gap-4 text-primary">
                    <span class="material-symbols-outlined text-secondary" data-icon="calendar_month">calendar_month</span>
                    Jadwal Ujian Mendatang
                </h3>
                
                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-surface-container-low">
                            <tr>
                                <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Mata Ujian</th>
                                <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider hidden sm:table-cell">Tanggal</th>
                                <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider hidden md:table-cell">Waktu</th>
                                <th class="px-6 py-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Sesi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container">
                            <tr class="hover:bg-surface-container transition-colors">
                                <td class="px-6 py-6 font-label-md text-label-md font-bold">
                                    Wawancara Akademik
                                    <div class="sm:hidden text-xs text-on-surface-variant font-normal mt-1">24 Okt 2024 • 09:00</div>
                                </td>
                                <td class="px-6 py-6 text-on-surface-variant hidden sm:table-cell">24 Okt 2024</td>
                                <td class="px-6 py-6 text-on-surface-variant hidden md:table-cell">09:00 - 10:30</td>
                                <td class="px-6 py-6">
                                    <span class="px-4 py-1 bg-secondary-container text-on-secondary-container rounded-full text-xs">Sesi 1</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container transition-colors">
                                <td class="px-6 py-6 font-label-md text-label-md font-bold">
                                    Tes Minat Bakat
                                    <div class="sm:hidden text-xs text-on-surface-variant font-normal mt-1">25 Okt 2024 • 13:00</div>
                                </td>
                                <td class="px-6 py-6 text-on-surface-variant hidden sm:table-cell">25 Okt 2024</td>
                                <td class="px-6 py-6 text-on-surface-variant hidden md:table-cell">13:00 - 15:00</td>
                                <td class="px-6 py-6">
                                    <span class="px-4 py-1 bg-secondary-container text-on-secondary-container rounded-full text-xs">Sesi 2</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Hasil & Riwayat Section -->
            <section>
                <h3 class="font-headline-md text-headline-md mb-6 flex items-center gap-4 text-primary">
                    <span class="material-symbols-outlined text-secondary" data-icon="assignment_turned_in">assignment_turned_in</span>
                    Hasil & Riwayat Ujian
                </h3>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="p-6 rounded-2xl border border-outline-variant bg-surface-container-lowest flex items-center gap-6">
                        <div class="w-12 h-12 flex-shrink-0 bg-secondary-container text-on-secondary-container rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined" data-icon="task_alt">task_alt</span>
                        </div>
                        <div>
                            <h5 class="font-label-md text-label-md font-bold">Ujian Dasar Matematika</h5>
                            <p class="text-label-sm text-secondary font-bold">Selesai - Lulus</p>
                            <p class="text-xs text-outline mt-1">Diselesaikan pada 20 Okt 2024</p>
                        </div>
                    </div>
                    
                    <div class="p-6 rounded-2xl border border-outline-variant bg-surface-container-lowest flex items-center gap-6 opacity-75">
                        <div class="w-12 h-12 flex-shrink-0 bg-surface-container-high text-on-surface-variant rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined" data-icon="pending">pending</span>
                        </div>
                        <div>
                            <h5 class="font-label-md text-label-md font-bold">Tes Karakter Pribadi</h5>
                            <p class="text-label-sm text-on-surface-variant">Selesai - Menunggu Hasil</p>
                            <p class="text-xs text-outline mt-1">Diselesaikan pada 21 Okt 2024</p>
                        </div>
                    </div>
                </div>
            </section>
            
        </div>
        
        <!-- Right Column (Sidebar/Info) -->
        <div class="flex flex-col gap-10">
            
            <!-- Technical Instruction Card -->
            <div class="bg-surface-container-high border border-outline-variant p-6 rounded-3xl">
                <div class="flex items-center gap-4 mb-6">
                    <div class="p-2 bg-tertiary-container text-on-tertiary-container rounded-lg">
                        <span class="material-symbols-outlined" data-icon="info">info</span>
                    </div>
                    <h4 class="font-headline-sm text-headline-sm">Instruksi Teknis</h4>
                </div>
                
                <ul class="space-y-4 mb-8">
                    <li class="flex gap-4">
                        <span class="material-symbols-outlined text-primary flex-shrink-0" data-icon="browser_updated">browser_updated</span>
                        <p class="text-label-md">Gunakan browser <strong>Google Chrome</strong> versi terbaru.</p>
                    </li>
                    <li class="flex gap-4">
                        <span class="material-symbols-outlined text-primary flex-shrink-0" data-icon="wifi">wifi</span>
                        <p class="text-label-md">Pastikan koneksi internet stabil (minimal 5 Mbps).</p>
                    </li>
                    <li class="flex gap-4">
                        <span class="material-symbols-outlined text-primary flex-shrink-0" data-icon="videocam">videocam</span>
                        <p class="text-label-md">Webcam harus selalu aktif selama ujian berlangsung.</p>
                    </li>
                    <li class="flex gap-4">
                        <span class="material-symbols-outlined text-primary flex-shrink-0" data-icon="no_accounts">no_accounts</span>
                        <p class="text-label-md">Dilarang membuka tab lain atau meninggalkan layar ujian.</p>
                    </li>
                </ul>
                
                <button class="w-full flex items-center justify-center gap-4 py-4 border border-primary text-primary rounded-xl font-label-md text-label-md hover:bg-primary/5 transition-colors bg-white">
                    <span class="material-symbols-outlined text-sm" data-icon="download">download</span>
                    Panduan Lengkap PDF
                </button>
            </div>
            
            <!-- Statistics Bento Card -->
            <div class="bg-secondary p-6 rounded-3xl text-on-secondary shadow-lg">
                <p class="font-label-sm text-label-sm opacity-80 mb-1">Status Kelolosan</p>
                <h4 class="font-headline-md text-headline-md mb-6">Proses Penilaian</h4>
                
                <div class="relative h-32 flex items-center justify-center">
                    <!-- Circular Progress Simulation -->
                    <svg class="w-24 h-24 transform -rotate-90">
                        <circle class="opacity-20" cx="48" cy="48" fill="transparent" r="40" stroke="currentColor" stroke-width="8"></circle>
                        <circle cx="48" cy="48" fill="transparent" r="40" stroke="#BBDC12" stroke-dasharray="251.2" stroke-dashoffset="62.8" stroke-width="8" stroke-linecap="round"></circle>
                    </svg>
                    <span class="absolute font-display-lg text-headline-md">75%</span>
                </div>
                <p class="text-center text-label-sm mt-6 opacity-90 italic">"Selangkah lagi menuju Hitech School!"</p>
            </div>
            
            <!-- Support Card -->
            <div class="bg-surface-container-lowest border border-outline-variant p-6 rounded-3xl">
                <div class="w-full h-32 bg-cover bg-center rounded-2xl mb-6" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAHabsK4IENgG8TOjsxPIZAvjMIQKDmPVa4I5xiM9YIwVVrdEVC0ga1_Yg5uQnDIy1IStGBMaIdwjz8nVo0mDpT4QHNPmUfZaxFv-1rrYCz4i0k53sIWGeUuPXG6dcPadB0FSs5e3wtrhcDSwiVu20N2linmQQxX7bXNXQ9poJ5uPdOO1P8clUw5avBzJ4M6kdr4DtIH08xMxBMIGtU8qg8BOVFuJVLmE7x1qJ_cBv4Og1L3-HTSonq')"></div>
                <h4 class="font-label-md text-label-md font-bold mb-2">Kendala Teknis?</h4>
                <p class="text-on-surface-variant text-label-md mb-6">Pengawas kami siap membantu Anda 24/7 selama masa ujian berlangsung.</p>
                
                <a href="https://wa.me/62882019679350" target="_blank" class="w-full flex justify-center bg-[#BBDC12] text-primary py-4 rounded-xl font-bold transition-transform hover:scale-[1.02] active:scale-95 text-center">
                    Hubungi Pengawas Sekarang
                </a>
            </div>
            
        </div>
    </div>

    @push('scripts')
    <script>
        // Simple Greeting Time-based Logic
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
