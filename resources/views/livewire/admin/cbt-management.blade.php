<x-slot:header>
    <span class="font-headline-sm text-headline-sm font-semibold text-on-surface">CBT Management</span>
</x-slot:header>

<x-slot:actions>
    <button
        wire:click="scheduleExam"
        class="hidden"
        id="submit-schedule-btn"
    ></button>
</x-slot:actions>

<div class="flex-1 overflow-y-auto p-gutter custom-scrollbar">

    {{-- Page Header --}}
    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-lg">
        <div>
            <h2 class="font-headline-md text-headline-md text-on-surface">Computer-Based Test (CBT)</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Oversee question banks, monitor live sessions, and manage scheduling.</p>
        </div>
        <div class="flex gap-sm flex-shrink-0">
            <button class="flex items-center gap-xs px-md py-sm bg-surface-container-lowest border border-outline-variant rounded-lg font-label-md text-label-md text-primary hover:bg-surface-container-high transition-all">
                <span class="material-symbols-outlined text-[20px]">cloud_download</span>
                Export Statistics
            </button>
            <button
                x-data
                @click="document.getElementById('exam-scheduler-section').scrollIntoView({ behavior: 'smooth' })"
                class="flex items-center gap-xs px-md py-sm bg-primary text-on-primary rounded-lg font-label-md text-label-md active:scale-95 transition-all"
            >
                <span class="material-symbols-outlined text-[20px]">add</span>
                Create New Exam
            </button>
        </div>
    </header>

    {{-- Success notification --}}
    @if($scheduledSuccess)
    <div
        x-data
        x-init="setTimeout(() => $wire.dismissSuccess(), 4000)"
        class="mb-md flex items-center gap-sm px-md py-sm bg-secondary-container text-on-secondary-container rounded-lg border border-primary/20 font-label-md text-label-md"
    >
        <span class="material-symbols-outlined text-[20px]">check_circle</span>
        Ujian berhasil dijadwalkan!
        <button wire:click="dismissSuccess" class="ml-auto hover:opacity-70">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    </div>
    @endif

    {{-- Top Row: Bento Stats & Monitoring --}}
    <div class="grid grid-cols-12 gap-gutter mb-lg">

        {{-- Live Monitoring Card (Large) --}}
        <div class="col-span-12 xl:col-span-8 bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                <div class="flex items-center gap-sm">
                    <div class="w-3 h-3 bg-error rounded-full animate-pulse"></div>
                    <h3 class="font-headline-sm text-headline-sm">Live Monitoring</h3>
                </div>
                <span class="font-label-sm text-label-sm px-sm py-1 bg-secondary-container text-on-secondary-container rounded-full">3 Active Sessions</span>
            </div>
            <div class="p-md">
                <div class="flex gap-md overflow-x-auto pb-sm custom-scrollbar" x-data="liveMonitor()">

                    {{-- Student Monitor Items --}}
                    <template x-for="student in students" :key="student.id">
                        <div
                            :class="student.status === 'critical'
                                ? 'min-w-[200px] p-sm bg-error-container/10 rounded-lg border border-error/20 cursor-pointer transition-all'
                                : 'min-w-[200px] p-sm bg-surface-container-low rounded-lg border border-outline-variant hover:border-primary transition-all cursor-pointer'"
                        >
                            <div class="flex items-center gap-sm mb-sm">
                                <div
                                    :class="student.status === 'critical'
                                        ? 'w-10 h-10 rounded-full bg-error-container flex items-center justify-center text-error font-bold'
                                        : 'w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold'"
                                    x-text="student.initials"
                                ></div>
                                <div>
                                    <p class="font-label-md text-label-md leading-none" x-text="student.name"></p>
                                    <p
                                        :class="student.status === 'critical' ? 'font-label-sm text-[10px] text-error' : 'font-label-sm text-[10px] text-on-surface-variant'"
                                        x-text="'Reg ID: #' + student.regId"
                                    ></p>
                                </div>
                            </div>
                            <div class="space-y-base">
                                <div class="flex justify-between text-label-sm">
                                    <span class="text-on-surface-variant">Progress</span>
                                    <span
                                        :class="student.status === 'critical' ? 'text-error font-bold' : 'text-primary font-bold'"
                                        x-text="student.progress + '%'"
                                    ></span>
                                </div>
                                <div
                                    :class="student.status === 'critical' ? 'h-2 w-full bg-error-container/50 rounded-full overflow-hidden' : 'h-2 w-full bg-secondary-container/20 rounded-full overflow-hidden'"
                                >
                                    <div
                                        :class="student.status === 'critical' ? 'h-full bg-error transition-all duration-1000' : 'h-full bg-tertiary-fixed-dim transition-all duration-1000'"
                                        :style="'width: ' + student.progress + '%'"
                                    ></div>
                                </div>
                                <div class="pt-base flex justify-between items-center">
                                    <span
                                        :class="student.status === 'critical' ? 'text-[10px] text-error font-bold' : 'text-[10px] text-on-surface-variant'"
                                        x-text="student.status === 'critical' ? 'Lost Connection' : 'Time Left: ' + student.timeLeft"
                                    ></span>
                                    <span
                                        :class="student.status === 'critical'
                                            ? 'text-[10px] bg-error text-on-error px-1 rounded'
                                            : 'text-[10px] text-on-secondary-container bg-secondary-container px-1 rounded'"
                                        x-text="student.status === 'critical' ? 'Critical' : 'Stable'"
                                    ></span>
                                </div>
                            </div>
                        </div>
                    </template>

                </div>
            </div>
        </div>

        {{-- Quick Stats Card (Small) --}}
        <div class="col-span-12 xl:col-span-4 bg-primary p-md rounded-xl text-on-primary flex flex-col justify-between relative overflow-hidden shadow-lg">
            <div class="relative z-10">
                <h4 class="font-label-md text-label-md opacity-80 mb-base">Total Questions in Bank</h4>
                <p class="font-display-lg-mobile text-display-lg-mobile font-black">{{ number_format($totalQuestions) }}</p>
            </div>
            <div class="relative z-10 mt-md pt-md border-t border-on-primary/10">
                <div class="flex justify-between items-center">
                    <span class="font-label-sm text-label-sm opacity-80">{{ $subjects->count() }} Subjects Available</span>
                    <div class="flex -space-x-2">
                        <div class="w-6 h-6 rounded-full bg-white/20 border border-white/40 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[14px]">description</span>
                        </div>
                        <div class="w-6 h-6 rounded-full bg-white/20 border border-white/40 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[14px]">table_chart</span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Abstract BG Decor --}}
            <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-secondary rounded-full opacity-20 blur-2xl"></div>
            <div class="absolute -left-4 -top-4 w-24 h-24 bg-primary-fixed opacity-10 blur-xl"></div>
        </div>

    </div>

    {{-- Middle Row: Question Bank & Scheduler --}}
    <div class="grid grid-cols-12 gap-gutter">

        {{-- Question Bank Table Section --}}
        <section class="col-span-12 xl:col-span-8">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm">
                <div class="p-md border-b border-outline-variant flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <h3 class="font-headline-sm text-headline-sm">Question Bank</h3>
                    <div class="flex gap-base">
                        <button class="flex items-center gap-xs px-sm py-base bg-secondary-container text-on-secondary-container rounded font-label-sm text-label-sm transition-all hover:brightness-95">
                            <span class="material-symbols-outlined text-[18px]">table_chart</span>
                            Import Excel
                        </button>
                        <button class="flex items-center gap-xs px-sm py-base bg-surface-container-high text-on-surface-variant rounded font-label-sm text-label-sm transition-all hover:brightness-95">
                            <span class="material-symbols-outlined text-[18px]">description</span>
                            Import Word
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-surface-container-low border-b border-outline-variant">
                            <tr>
                                <th class="p-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Subject</th>
                                <th class="p-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Topic</th>
                                <th class="p-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-center">Items</th>
                                <th class="p-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Difficulty</th>
                                <th class="p-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Last Modified</th>
                                <th class="p-md"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @forelse($subjects as $subject)
                            <tr class="hover:bg-surface-container-low transition-colors group" wire:key="subject-{{ $subject->id }}">
                                <td class="p-md">
                                    <div class="flex items-center gap-sm">
                                        @php
                                            $bgMap = [
                                                'Hard'   => 'bg-error/10 text-error',
                                                'Medium' => 'bg-secondary-container/50 text-secondary',
                                                'Easy'   => 'bg-primary-fixed/30 text-primary',
                                            ];
                                            $bg = $bgMap[$subject->difficulty] ?? 'bg-surface-container text-on-surface-variant';
                                        @endphp
                                        <span class="w-8 h-8 rounded {{ $bg }} flex items-center justify-center font-bold text-xs">{{ $subject->code }}</span>
                                        <span class="font-body-md text-body-md font-semibold">{{ $subject->name }}</span>
                                    </div>
                                </td>
                                <td class="p-md font-body-md text-body-md text-on-surface-variant">{{ $subject->topic }}</td>
                                <td class="p-md font-body-md text-body-md text-center">{{ number_format($subject->items_count) }}</td>
                                <td class="p-md">
                                    @if($subject->difficulty === 'Hard')
                                        <span class="px-2 py-0.5 bg-error-container/30 text-error rounded text-[11px] font-bold">Hard</span>
                                    @elseif($subject->difficulty === 'Medium')
                                        <span class="px-2 py-0.5 bg-secondary-container text-on-secondary-container rounded text-[11px] font-bold">Medium</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-primary-fixed text-on-primary-fixed-variant rounded text-[11px] font-bold">Easy</span>
                                    @endif
                                </td>
                                <td class="p-md font-label-md text-label-md text-on-surface-variant">{{ $subject->updated_at->format('M d, Y') }}</td>
                                <td class="p-md text-right">
                                    <button class="opacity-0 group-hover:opacity-100 transition-opacity text-on-surface-variant hover:text-primary">
                                        <span class="material-symbols-outlined">edit</span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-md text-center text-on-surface-variant font-body-md text-body-md">
                                    No subjects in the question bank yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-md border-t border-outline-variant flex justify-between items-center text-label-sm text-on-surface-variant">
                    <span>Showing {{ $subjects->count() }} of {{ $subjects->count() }} subjects</span>
                    <button class="text-primary font-bold hover:underline font-label-sm text-label-sm">View All Subjects</button>
                </div>
            </div>
        </section>

        {{-- Exam Scheduling Form Section --}}
        <section class="col-span-12 xl:col-span-4" id="exam-scheduler-section">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-md">
                <h3 class="font-headline-sm text-headline-sm mb-md flex items-center gap-xs">
                    <span class="material-symbols-outlined text-primary">event</span>
                    Quick Scheduler
                </h3>

                <form wire:submit="scheduleExam" class="space-y-md">
                    {{-- Exam Name --}}
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Exam Name</label>
                        <input
                            wire:model="examName"
                            type="text"
                            placeholder="e.g. Midterm Batch A"
                            class="w-full bg-surface border rounded-lg px-sm py-base font-body-md text-body-md focus:ring-2 focus:ring-primary focus:border-primary transition-all {{ $errors->has('examName') ? 'border-error focus:ring-error' : 'border-outline-variant' }}"
                        />
                        @error('examName')
                            <p class="mt-1 text-[11px] text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date & Session --}}
                    <div class="grid grid-cols-2 gap-sm">
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Date</label>
                            <input
                                wire:model="examDate"
                                type="date"
                                min="{{ now()->format('Y-m-d') }}"
                                class="w-full bg-surface border rounded-lg px-sm py-base font-body-md text-body-md focus:ring-2 focus:ring-primary focus:border-primary transition-all {{ $errors->has('examDate') ? 'border-error focus:ring-error' : 'border-outline-variant' }}"
                            />
                            @error('examDate')
                                <p class="mt-1 text-[11px] text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Session</label>
                            <select
                                wire:model="examSession"
                                class="w-full bg-surface border rounded-lg px-sm py-base font-body-md text-body-md focus:ring-2 focus:ring-primary focus:border-primary transition-all {{ $errors->has('examSession') ? 'border-error' : 'border-outline-variant' }}"
                            >
                                @foreach($sessions as $session)
                                    <option value="{{ $session }}">{{ $session }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Assigned Room --}}
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Assigned Room</label>
                        <div class="grid grid-cols-2 gap-xs">
                            @foreach($rooms as $room)
                            <label class="flex items-center gap-xs p-sm border rounded-lg cursor-pointer transition-all hover:bg-surface-container-low {{ $examRoom === $room ? 'border-primary bg-primary-fixed/20' : 'border-outline-variant' }}">
                                <input
                                    wire:model.live="examRoom"
                                    class="text-primary focus:ring-primary"
                                    name="room"
                                    type="radio"
                                    value="{{ $room }}"
                                />
                                <span class="font-label-md text-label-md">{{ $room }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('examRoom')
                            <p class="mt-1 text-[11px] text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Upcoming Exams --}}
                    @if($upcomingExams->count() > 0)
                    <div class="pt-sm border-t border-outline-variant">
                        <p class="font-label-sm text-label-sm text-on-surface-variant mb-xs">Upcoming Scheduled</p>
                        <div class="space-y-xs">
                            @foreach($upcomingExams as $exam)
                            <div class="flex items-center justify-between p-xs bg-surface-container-low rounded-lg" wire:key="exam-{{ $exam->id }}">
                                <div>
                                    <p class="font-label-md text-label-md text-on-surface truncate max-w-[130px]">{{ $exam->name }}</p>
                                    <p class="text-[10px] text-on-surface-variant">{{ \Carbon\Carbon::parse($exam->date)->format('M d') }} · {{ $exam->room }}</p>
                                </div>
                                <span class="text-[10px] bg-secondary-container text-on-secondary-container px-xs py-0.5 rounded font-bold">{{ explode(' ', $exam->session)[0] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Submit --}}
                    <div class="pt-sm">
                        <button
                            type="submit"
                            class="w-full bg-primary text-on-primary font-label-md text-label-md py-md rounded-lg active:scale-95 transition-all shadow-md"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-70 cursor-not-allowed"
                        >
                            <span wire:loading.remove>Confirm Schedule</span>
                            <span wire:loading class="flex items-center justify-center gap-sm">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Scheduling...
                            </span>
                        </button>
                        <p class="text-[11px] text-center text-on-surface-variant mt-sm">Admin credentials required to publish changes.</p>
                    </div>
                </form>
            </div>
        </section>

    </div>

    {{-- Footer --}}
    <footer class="mt-xl py-xl border-t border-outline-variant flex flex-col md:flex-row justify-between items-center w-full gap-4">
        <div class="flex flex-col gap-xs">
            <span class="font-label-md text-label-md font-bold text-on-background">Hitech School SPMB</span>
            <span class="font-label-sm text-label-sm text-on-surface-variant">© {{ date('Y') }} Hitech School Admission System. Built for Institutional Innovation.</span>
        </div>
        <div class="flex gap-md flex-wrap">
            <a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Privacy Policy</a>
            <a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Terms of Service</a>
            <a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Support</a>
        </div>
    </footer>

</div>

@push('scripts')
<script>
function liveMonitor() {
    return {
        students: [
            { id: 1, name: 'Jane Doe', initials: 'JD', regId: '8821', progress: 78, timeLeft: '42m', status: 'stable' },
            { id: 2, name: 'Mark Kim', initials: 'MK', regId: '8825', progress: 45, timeLeft: '1h 10m', status: 'stable' },
            { id: 3, name: 'Ali Syari', initials: 'AS', regId: '8832', progress: 12, timeLeft: null, status: 'critical' },
            { id: 4, name: 'Sara Young', initials: 'SY', regId: '8840', progress: 92, timeLeft: '5m', status: 'stable' },
        ],
        init() {
            setInterval(() => {
                this.students = this.students.map(s => {
                    if (s.status !== 'critical' && s.progress < 100 && Math.random() > 0.5) {
                        return { ...s, progress: Math.min(s.progress + 1, 100) };
                    }
                    return s;
                });
            }, 3000);
        }
    };
}
</script>
@endpush
