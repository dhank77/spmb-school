<div class="space-y-lg">

    {{-- Exam Portal Header Banner --}}
    <section class="relative overflow-hidden rounded-3xl bg-surface-container-lowest border border-outline-variant p-6 md:p-10 shadow-sm">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full mb-3">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">quiz</span>
                    <span class="font-label-sm text-label-sm font-bold">CBT Exam Center</span>
                </div>
                <h1 class="font-headline-md text-headline-md md:text-display-lg-mobile font-bold text-on-surface mb-2">
                    Portal Ujian & Jadwal Seleksi
                </h1>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Di bawah ini adalah ujian seleksi yang sedang berlangsung hari ini. Klik "Mulai Ujian" untuk mengerjakan tes berbasis komputer (CBT).
                </p>
            </div>
            <div class="flex items-center gap-3 bg-surface-container-low p-4 rounded-2xl border border-outline-variant flex-shrink-0">
                <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-2xl">assignment</span>
                </div>
                <div>
                    <p class="font-headline-sm text-headline-sm font-bold text-primary">{{ $activeExams->count() }}</p>
                    <p class="text-xs text-on-surface-variant font-medium">Ujian Sedang Berlangsung</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Grid Layout --}}
    <div class="grid lg:grid-cols-3 gap-6 lg:gap-10">

        {{-- Left Column (Active Exams & Schedule) --}}
        <div class="lg:col-span-2 flex flex-col gap-10">

            {{-- Ujian Aktif Siap Diikuti Section --}}
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-headline-md text-headline-md flex items-center gap-4 text-primary">
                        <span class="material-symbols-outlined text-secondary">timer</span>
                        Ujian Aktif Siap Diikuti
                    </h3>
                    <span class="px-4 py-1 bg-secondary-container text-on-secondary-container rounded-full font-label-sm text-label-sm font-bold">
                        {{ $activeExams->count() }} Ujian Berlangsung
                    </span>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    @forelse($activeExams as $exam)
                    @php
                        $isCompleted = in_array($exam->cbt_subject_id, $completedSubjectIds);
                    @endphp
                    <div class="glass-card exam-card-hover border border-outline-variant p-6 rounded-2xl flex flex-col h-full border-t-4 {{ $isCompleted ? 'border-t-secondary bg-surface-container-low/40' : 'border-t-primary bg-white' }} shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2 bg-primary/10 text-primary rounded-lg font-bold text-sm">
                                {{ $exam->subject ? $exam->subject->code : 'EXAM' }}
                            </div>
                            @if($isCompleted)
                                <span class="font-label-sm text-label-sm px-2.5 py-1 bg-secondary-container text-on-secondary-container font-bold rounded-full flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">check_circle</span> SUDAH DIKERJAKAN
                                </span>
                            @else
                                <span class="font-label-sm text-label-sm px-2.5 py-1 bg-primary-fixed/40 text-primary font-bold rounded-full">
                                    {{ $exam->session }}
                                </span>
                            @endif
                        </div>
                        <h4 class="font-headline-sm text-headline-sm font-bold mb-1">{{ $exam->name }}</h4>
                        <p class="text-on-surface-variant text-label-md mb-4">
                            {{ $exam->subject ? $exam->subject->name : 'Mata Ujian CBT' }} · Topik: {{ $exam->subject ? $exam->subject->topic : 'Akademik' }}
                        </p>

                        <div class="mt-auto space-y-4">
                            <div class="flex items-center justify-between text-on-surface-variant text-label-md bg-surface-container-low p-3 rounded-xl">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">meeting_room</span>
                                    <span class="font-bold">{{ $exam->room }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">description</span>
                                    <span class="font-bold">{{ $exam->subject ? $exam->subject->questions_count : 0 }} Soal</span>
                                </div>
                            </div>
                            @if($isCompleted)
                                <a
                                    href="{{ route('exam.cbt', $exam->cbt_subject_id) }}"
                                    class="w-full inline-block text-center bg-surface-container-high text-on-surface-variant py-4 rounded-xl font-headline-sm text-headline-sm transition-all hover:bg-surface-container-highest shadow-sm font-bold"
                                >
                                    Lihat Hasil Ujian
                                </a>
                            @else
                                <a
                                    href="{{ route('exam.cbt', $exam->cbt_subject_id) }}"
                                    class="w-full inline-block text-center bg-primary text-on-primary py-4 rounded-xl font-headline-sm text-headline-sm transition-all hover:bg-primary-container active:scale-95 shadow-md font-bold"
                                >
                                    Mulai Ujian Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 p-8 bg-surface-container-lowest border border-outline-variant rounded-2xl text-center">
                        <span class="material-symbols-outlined text-4xl text-outline mb-2">event_busy</span>
                        <p class="text-on-surface font-bold text-headline-sm mb-1">Tidak Ada Ujian Aktif Hari Ini</p>
                        <p class="text-on-surface-variant text-body-md">Silakan periksa jadwal ujian mendatang di tabel bawah.</p>
                    </div>
                    @endforelse
                </div>
            </section>

            {{-- Jadwal Ujian Mendatang Section --}}
            <section>
                <h3 class="font-headline-md text-headline-md mb-6 flex items-center gap-4 text-primary">
                    <span class="material-symbols-outlined text-secondary">calendar_month</span>
                    Jadwal Ujian Mendatang
                </h3>

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
                            @forelse($upcomingExams as $exam)
                            <tr class="hover:bg-surface-container transition-colors">
                                <td class="px-6 py-6 font-label-md text-label-md font-bold">
                                    {{ $exam->name }}
                                    @if($exam->subject)
                                        <span class="ml-2 px-2 py-0.5 text-xs bg-primary/10 text-primary font-bold rounded">{{ $exam->subject->code }}</span>
                                    @endif
                                    <div class="sm:hidden text-xs text-on-surface-variant font-normal mt-1">
                                        {{ \Carbon\Carbon::parse($exam->date)->format('d M Y') }} • {{ $exam->session }}
                                    </div>
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

            {{-- Hasil & Riwayat Section --}}
            <section>
                <h3 class="font-headline-md text-headline-md mb-6 flex items-center gap-4 text-primary">
                    <span class="material-symbols-outlined text-secondary">assignment_turned_in</span>
                    Hasil & Riwayat Ujian
                </h3>

                <div class="grid md:grid-cols-2 gap-6">
                    @forelse($completedResults as $res)
                    <div class="p-6 rounded-2xl border border-outline-variant bg-surface-container-lowest flex items-center gap-6 shadow-sm">
                        <div class="w-12 h-12 flex-shrink-0 bg-secondary-container text-on-secondary-container rounded-full flex items-center justify-center font-bold">
                            <span class="material-symbols-outlined text-2xl">task_alt</span>
                        </div>
                        <div class="flex-grow">
                            <h5 class="font-label-md text-label-md font-bold">{{ $res->subject ? $res->subject->name : 'Ujian CBT' }}</h5>
                            <p class="text-label-sm text-secondary font-bold">Selesai - Skor: {{ $res->score }}/{{ $res->total_points }} ({{ $res->correct_count }}/{{ $res->total_questions }} Benar)</p>
                            <p class="text-xs text-outline mt-1">Diselesaikan pada {{ \Carbon\Carbon::parse($res->completed_at)->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 p-6 rounded-2xl border border-outline-variant bg-surface-container-lowest text-center">
                        <p class="text-on-surface-variant font-body-md text-body-md">Belum ada riwayat ujian yang diselesaikan.</p>
                    </div>
                    @endforelse
                </div>
            </section>

        </div>

        {{-- Right Column (Sidebar/Info) --}}
        <div class="flex flex-col gap-10">

            {{-- Technical Instruction Card --}}
            <div class="bg-surface-container-high border border-outline-variant p-6 rounded-3xl">
                <div class="flex items-center gap-4 mb-6">
                    <div class="p-2 bg-tertiary-container text-on-tertiary-container rounded-lg">
                        <span class="material-symbols-outlined">info</span>
                    </div>
                    <h4 class="font-headline-sm text-headline-sm">Instruksi Teknis</h4>
                </div>

                <ul class="space-y-4 mb-8">
                    <li class="flex gap-4">
                        <span class="material-symbols-outlined text-primary flex-shrink-0">browser_updated</span>
                        <p class="text-label-md">Gunakan browser <strong>Google Chrome</strong> versi terbaru.</p>
                    </li>
                    <li class="flex gap-4">
                        <span class="material-symbols-outlined text-primary flex-shrink-0">wifi</span>
                        <p class="text-label-md">Pastikan koneksi internet stabil (minimal 5 Mbps).</p>
                    </li>
                    <li class="flex gap-4">
                        <span class="material-symbols-outlined text-primary flex-shrink-0">videocam</span>
                        <p class="text-label-md">Webcam harus selalu aktif selama ujian berlangsung.</p>
                    </li>
                    <li class="flex gap-4">
                        <span class="material-symbols-outlined text-primary flex-shrink-0">no_accounts</span>
                        <p class="text-label-md">Dilarang membuka tab lain atau meninggalkan layar ujian.</p>
                    </li>
                </ul>

                <button class="w-full flex items-center justify-center gap-4 py-4 border border-primary text-primary rounded-xl font-label-md text-label-md hover:bg-primary/5 transition-colors bg-white">
                    <span class="material-symbols-outlined text-sm">download</span>
                    Panduan Lengkap PDF
                </button>
            </div>

            {{-- Statistics Bento Card --}}
            <div class="bg-secondary p-6 rounded-3xl text-on-secondary shadow-lg">
                <p class="font-label-sm text-label-sm opacity-80 mb-1">Status Kelolosan</p>
                <h4 class="font-headline-md text-headline-md mb-6">Proses Penilaian</h4>

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

</div>
