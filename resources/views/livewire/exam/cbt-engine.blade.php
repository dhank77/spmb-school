<div class="space-y-md select-none" x-data="{ mobileNavOpen: false }">

    {{-- Exam Header Bar --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-md">
        <div class="flex items-center gap-sm">
            <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary font-bold">
                <span class="material-symbols-outlined">quiz</span>
            </div>
            <div>
                <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">
                    {{ $subject ? $subject->name : 'Mata Ujian CBT' }}
                </h2>
                <p class="text-label-sm text-on-surface-variant font-medium">
                    Topik: {{ $subject ? $subject->topic : 'Ujian Akademik' }} · {{ $totalQuestionsCount }} Soal
                </p>
            </div>
        </div>

        {{-- Timer Display --}}
        <div
            x-data="examTimer({{ $remainingSeconds }})"
            class="flex items-center gap-xs md:gap-sm px-md py-2 rounded-full border transition-all"
            :class="isLowTime ? 'bg-error-container text-error border-error animate-pulse' : 'bg-secondary-container text-on-secondary-container border-secondary/20'"
        >
            <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">timer</span>
            <span class="font-headline-sm text-headline-sm font-bold tracking-tighter" x-text="formattedTime"></span>
        </div>
    </div>

    {{-- Main Content Layout --}}
    <div class="flex flex-col md:flex-row gap-gutter overflow-hidden">

        {{-- Left Column: Question Canvas --}}
        <section class="flex-grow flex flex-col gap-md min-w-0">

            @if($isSubmitted)
                {{-- Exam Finished Results View --}}
                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-xl shadow-md text-center my-auto">
                    <div class="w-16 h-16 rounded-full bg-primary-fixed text-primary flex items-center justify-center mx-auto mb-md">
                        <span class="material-symbols-outlined text-[36px]">verified</span>
                    </div>
                    <h2 class="font-headline-md text-headline-md text-on-surface mb-xs">Ujian Selesai!</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant mb-lg">Jawaban Anda telah berhasil dikirim dan tersimpan di sistem.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-[540px] w-full mx-auto my-6 p-6 bg-surface-container-low rounded-2xl border border-outline-variant shadow-sm">
                        <div class="p-3 bg-white rounded-xl border border-outline-variant/60">
                            <p class="text-[11px] text-on-surface-variant font-bold uppercase tracking-wider mb-1">Terjawab</p>
                            <p class="font-headline-md text-headline-md text-primary font-black">{{ count($answers) > 0 ? count($answers) : $totalQuestionsCount }}/{{ $totalQuestionsCount }}</p>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-outline-variant/60">
                            <p class="text-[11px] text-on-surface-variant font-bold uppercase tracking-wider mb-1">Benar</p>
                            <p class="font-headline-md text-headline-md text-secondary font-black">{{ $correctCount }}/{{ $totalQuestionsCount }}</p>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-outline-variant/60">
                            <p class="text-[11px] text-on-surface-variant font-bold uppercase tracking-wider mb-1">Skor Total</p>
                            <p class="font-headline-md text-headline-md text-primary font-black">{{ $finalScore }}</p>
                        </div>
                    </div>

                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-sm px-xl py-3 bg-primary text-on-primary font-bold rounded-lg hover:bg-primary-container transition-all">
                        <span class="material-symbols-outlined">dashboard</span>
                        Kembali ke Dashboard
                    </a>
                </div>
            @else
                {{-- Active Question Canvas --}}
                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl exam-shadow flex-grow flex flex-col min-h-[480px]">

                    {{-- Question Header --}}
                    <div class="p-md border-b border-outline-variant flex items-center justify-between bg-surface-container-low/30">
                        <div class="flex items-center gap-xs">
                            <span class="bg-primary text-on-primary px-3 py-1 rounded text-label-md font-bold">
                                Soal {{ $currentIndex + 1 }}
                            </span>
                            <span class="text-on-surface-variant font-label-md">/ {{ $totalQuestionsCount }}</span>
                        </div>

                        {{-- Flag Toggle Button --}}
                        <button
                            wire:click="toggleFlag"
                            class="flex items-center gap-1 px-3 py-1 rounded transition-colors {{ ($flagged[$currentIndex] ?? false) ? 'text-tertiary font-bold bg-tertiary-fixed/30' : 'text-primary hover:bg-primary-fixed/20' }}"
                        >
                            <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' {{ ($flagged[$currentIndex] ?? false) ? 1 : 0 }};">
                                bookmark
                            </span>
                            <span class="font-label-md">
                                {{ ($flagged[$currentIndex] ?? false) ? 'Tandai Ragu' : 'Tandai Ragu' }}
                            </span>
                        </button>
                    </div>

                    {{-- Question Text & Options --}}
                    <div class="p-md md:p-xl overflow-y-auto scrollbar-hide flex-grow">
                        <div class="max-w-2xl mx-auto">
                            <h2 class="font-headline-md text-headline-md mb-lg leading-relaxed text-on-surface">
                                {{ $currentQuestion['question_text'] ?? 'Pertanyaan tidak ditemukan.' }}
                            </h2>

                            {{-- Options List --}}
                            <div class="grid grid-cols-1 gap-sm">
                                @foreach(['A' => $currentQuestion['option_a'] ?? '', 'B' => $currentQuestion['option_b'] ?? '', 'C' => $currentQuestion['option_c'] ?? '', 'D' => $currentQuestion['option_d'] ?? ''] as $key => $optionValue)
                                    @php
                                        $isSelected = isset($answers[$currentIndex]) && $answers[$currentIndex] === $key;
                                    @endphp
                                    <label
                                        wire:click="selectOption('{{ $key }}')"
                                        class="group relative flex items-center p-md border rounded-xl cursor-pointer transition-all duration-200 {{ $isSelected ? 'border-primary bg-primary-fixed/15 ring-2 ring-primary/20' : 'border-outline-variant hover:border-primary hover:bg-primary-fixed/10' }}"
                                    >
                                        <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center text-label-md font-bold transition-colors {{ $isSelected ? 'bg-primary border-primary text-white' : 'border-outline-variant text-on-surface-variant group-hover:border-primary' }}">
                                            {{ $key }}
                                        </div>
                                        <span class="ml-md font-body-md text-on-surface">{{ $optionValue }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Control Buttons --}}
                    <div class="p-md border-t border-outline-variant flex items-center justify-between bg-surface-container-low/30">
                        <button
                            wire:click="previousQuestion"
                            @if($currentIndex === 0) disabled @endif
                            class="flex items-center gap-2 px-md py-3 text-primary font-bold border border-primary rounded-lg hover:bg-primary-fixed/20 transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed"
                        >
                            <span class="material-symbols-outlined">chevron_left</span>
                            Sebelumnya
                        </button>

                        <div class="hidden md:flex gap-sm">
                            @if(isset($answers[$currentIndex]))
                                <button
                                    wire:click="clearAnswer"
                                    class="px-md py-3 text-error font-bold hover:bg-error-container/40 rounded-lg transition-all"
                                >
                                    Hapus Jawaban
                                </button>
                            @endif
                        </div>

                        @if($currentIndex < $totalQuestionsCount - 1)
                            <button
                                wire:click="nextQuestion"
                                class="flex items-center gap-2 px-xl py-3 bg-primary text-on-primary font-bold rounded-lg shadow-md hover:bg-surface-tint transition-all active:scale-95"
                            >
                                Selanjutnya
                                <span class="material-symbols-outlined">chevron_right</span>
                            </button>
                        @else
                            <button
                                wire:click="finishExam"
                                wire:confirm="Apakah Anda yakin ingin menyelesaikan ujian sekarang?"
                                class="flex items-center gap-2 px-xl py-3 bg-secondary text-on-secondary font-black rounded-lg shadow-md hover:opacity-90 transition-all active:scale-95"
                            >
                                SELESAIKAN UJIAN
                            </button>
                        @endif
                    </div>
                </div>
            @endif

        </section>

        {{-- Right Column: Question Navigator --}}
        <aside
            class="fixed inset-x-0 bottom-0 z-[60] md:relative md:inset-auto md:z-0 md:w-[300px] bg-surface-container-lowest md:bg-transparent border-t md:border-t-0 border-outline-variant p-md md:p-0 flex flex-col rounded-t-2xl md:rounded-none transition-transform duration-300 md:translate-y-0"
            :class="mobileNavOpen ? 'translate-y-0 shadow-2xl' : 'translate-y-full md:translate-y-0'"
        >
            <div class="md:hidden w-12 h-1.5 bg-outline-variant rounded-full mx-auto mb-md cursor-pointer" @click="mobileNavOpen = false"></div>

            <div class="flex flex-col h-full bg-surface-container-lowest md:border md:border-outline-variant md:rounded-2xl md:exam-shadow overflow-hidden">
                <div class="p-md border-b border-outline-variant bg-surface-container-low/50 flex justify-between items-center">
                    <h3 class="font-label-md text-label-md font-bold text-on-surface">Navigasi Soal</h3>
                    <button class="md:hidden text-on-surface-variant" @click="mobileNavOpen = false">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                {{-- Question Grid --}}
                <div class="flex-grow p-md overflow-y-auto scrollbar-hide max-h-[380px] md:max-h-none">
                    <div class="grid grid-cols-5 gap-xs">
                        @for($i = 0; $i < $totalQuestionsCount; $i++)
                            @php
                                $isCurrent = ($currentIndex === $i);
                                $isAnswered = isset($answers[$i]);
                                $isFlagged = $flagged[$i] ?? false;

                                $btnClass = 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container-high';
                                if ($isAnswered) {
                                    $btnClass = 'bg-primary text-white';
                                }
                                if ($isCurrent) {
                                    $btnClass .= ' ring-2 ring-primary border-2 border-primary font-black';
                                }
                            @endphp
                            <button
                                wire:click="goToQuestion({{ $i }})"
                                class="relative w-full aspect-square flex items-center justify-center rounded-lg text-xs font-bold transition-all duration-200 {{ $btnClass }}"
                            >
                                {{ $i + 1 }}
                                @if($isFlagged)
                                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-tertiary rounded-full border-2 border-white"></span>
                                @endif
                            </button>
                        @endfor
                    </div>
                </div>

                {{-- Status Legend & Finish Button --}}
                <div class="p-md border-t border-outline-variant bg-surface-container-low/50">
                    <div class="grid grid-cols-2 gap-sm mb-md text-[10px] uppercase font-bold text-on-surface-variant">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-sm bg-primary"></div> Terjawab
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-sm bg-surface-container-low border border-outline-variant"></div> Belum Dijawab
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-tertiary"></div> Ragu-ragu
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-sm border-2 border-primary"></div> Posisi Saat Ini
                        </div>
                    </div>

                    @if(!$isSubmitted)
                        <button
                            wire:click="finishExam"
                            wire:confirm="Apakah Anda yakin ingin menyelesaikan ujian sekarang?"
                            class="w-full py-3 bg-secondary text-on-secondary font-black rounded-lg shadow hover:opacity-90 active:scale-[0.98] transition-all"
                        >
                            SELESAIKAN UJIAN
                        </button>
                    @endif
                </div>
            </div>
        </aside>
    </div>

    {{-- Backdrop overlay for mobile navigation --}}
    <div
        x-show="mobileNavOpen"
        x-transition.opacity
        class="fixed inset-0 bg-black/50 z-50 md:hidden"
        @click="mobileNavOpen = false"
        style="display: none;"
    ></div>

</div>

@push('scripts')
<script>
    function examTimer(seconds) {
        return {
            remaining: seconds,
            isLowTime: false,
            get formattedTime() {
                const mins = Math.floor(this.remaining / 60);
                const secs = this.remaining % 60;
                return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            },
            init() {
                setInterval(() => {
                    if (this.remaining > 0) {
                        this.remaining--;
                        if (this.remaining < 300) { // Under 5 minutes
                            this.isLowTime = true;
                        }
                    }
                }, 1000);
            }
        };
    }

    // Basic Anti-Cheat Protection
    window.addEventListener('contextmenu', e => e.preventDefault());
    window.addEventListener('keydown', e => {
        if (e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'u' || e.key === 's')) {
            e.preventDefault();
        }
    });
</script>
@endpush
