<x-slot:header>
    <div class="flex items-center gap-sm">
        <a href="{{ route('admin.cbt') }}" class="p-1 rounded-lg hover:bg-surface-container text-on-surface-variant transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <span class="font-headline-sm text-headline-sm font-semibold text-on-surface">Kelola Soal: {{ $subject->name }}</span>
    </div>
</x-slot:header>

<div class="flex-1 overflow-y-auto p-gutter custom-scrollbar">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-lg">
        <div>
            <div class="flex items-center gap-sm mb-1">
                <span class="px-2 py-0.5 bg-primary/10 text-primary font-bold rounded text-xs">{{ $subject->code }}</span>
                <h2 class="font-headline-md text-headline-md text-on-surface">{{ $subject->name }}</h2>
                <span class="font-label-sm text-label-sm text-on-surface-variant">({{ $subject->topic }})</span>
            </div>
            <p class="font-body-md text-body-md text-on-surface-variant">
                Total Soal: <strong class="text-primary">{{ number_format($subject->questions_count ?? $questions->total()) }}</strong> · Kesulitan: <strong>{{ $subject->difficulty }}</strong>
            </p>
        </div>
        <button
            wire:click="openQuestionModal"
            class="flex items-center gap-xs px-md py-sm bg-primary text-on-primary rounded-lg font-label-md text-label-md active:scale-95 transition-all shadow-sm"
        >
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tambah Soal Baru
        </button>
    </div>

    {{-- Questions List --}}
    <div class="space-y-md mb-lg">
        @forelse($questions as $index => $q)
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm transition-all hover:border-primary/50" wire:key="q-{{ $q->id }}">
            <div class="flex justify-between items-start gap-md mb-sm">
                <div class="flex items-start gap-sm">
                    <span class="w-7 h-7 rounded-full bg-primary-fixed text-primary font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                        {{ $questions->firstItem() + $index }}
                    </span>
                    <div class="font-body-md text-body-md text-on-surface font-medium leading-relaxed">
                        {{ $q->question_text }}
                    </div>
                </div>
                <div class="flex items-center gap-xs flex-shrink-0">
                    <span class="text-[11px] font-bold px-2 py-0.5 bg-secondary-container text-on-secondary-container rounded">
                        {{ $q->points }} Poin
                    </span>
                    <button
                        wire:click="editQuestion({{ $q->id }})"
                        class="p-1 text-on-surface-variant hover:text-primary transition-colors"
                        title="Edit Soal"
                    >
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </button>
                    <button
                        wire:click="deleteQuestion({{ $q->id }})"
                        wire:confirm="Hapus soal ini?"
                        class="p-1 text-on-surface-variant hover:text-error transition-colors"
                        title="Hapus Soal"
                    >
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
            </div>

            {{-- Options Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-xs pl-9">
                @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $key => $opt)
                <div class="flex items-center gap-xs p-xs rounded-lg text-label-md text-label-md {{ $q->correct_answer === $key ? 'bg-primary-fixed/20 border border-primary/40 text-primary font-semibold' : 'bg-surface-container-low text-on-surface-variant' }}">
                    <span class="w-5 h-5 rounded-full {{ $q->correct_answer === $key ? 'bg-primary text-on-primary font-bold' : 'bg-surface-container-highest text-on-surface-variant font-bold' }} flex items-center justify-center text-[10px]">
                        {{ $key }}
                    </span>
                    <span>{{ $opt }}</span>
                    @if($q->correct_answer === $key)
                        <span class="material-symbols-outlined text-[16px] ml-auto text-primary">check_circle</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl text-center">
            <span class="material-symbols-outlined text-[48px] text-on-surface-variant/40 mb-xs">quiz</span>
            <h3 class="font-headline-sm text-headline-sm text-on-surface mb-xs">Belum ada soal untuk subject ini</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-md">Klik tombol di bawah untuk menambahkan soal pertama.</p>
            <button
                wire:click="openQuestionModal"
                class="inline-flex items-center gap-xs px-md py-sm bg-primary text-on-primary rounded-lg font-label-md text-label-md active:scale-95 transition-all"
            >
                <span class="material-symbols-outlined text-[20px]">add</span>
                Tambah Soal Sekarang
            </button>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($questions->hasPages())
    <div class="mt-md">
        {{ $questions->links() }}
    </div>
    @endif

    {{-- Question Modal --}}
    @if($showQuestionModal)
    @teleport('body')
    <div class="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-md">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-xl w-full max-w-[640px] overflow-hidden">
            <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">
                    {{ $editingQuestionId ? 'Edit Soal' : 'Tambah Soal Baru' }}
                </h3>
                <button wire:click="closeQuestionModal" class="text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form wire:submit="saveQuestion" class="p-md space-y-md">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Pertanyaan</label>
                    <textarea
                        wire:model="questionText"
                        rows="3"
                        placeholder="Tuliskan teks soal/pertanyaan di sini..."
                        class="w-full bg-surface border border-outline-variant rounded-lg p-sm font-body-md text-body-md focus:ring-2 focus:ring-primary"
                    ></textarea>
                    @error('questionText') <p class="mt-1 text-[11px] text-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-sm">
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Opsi A</label>
                        <input wire:model="optionA" type="text" placeholder="Jawaban A" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-base font-body-md text-body-md focus:ring-2 focus:ring-primary" />
                        @error('optionA') <p class="mt-1 text-[11px] text-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Opsi B</label>
                        <input wire:model="optionB" type="text" placeholder="Jawaban B" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-base font-body-md text-body-md focus:ring-2 focus:ring-primary" />
                        @error('optionB') <p class="mt-1 text-[11px] text-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Opsi C</label>
                        <input wire:model="optionC" type="text" placeholder="Jawaban C" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-base font-body-md text-body-md focus:ring-2 focus:ring-primary" />
                        @error('optionC') <p class="mt-1 text-[11px] text-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Opsi D</label>
                        <input wire:model="optionD" type="text" placeholder="Jawaban D" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-base font-body-md text-body-md focus:ring-2 focus:ring-primary" />
                        @error('optionD') <p class="mt-1 text-[11px] text-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-sm">
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Kunci Jawaban</label>
                        <select wire:model="correctAnswer" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-base font-body-md text-body-md focus:ring-2 focus:ring-primary">
                            @foreach($answerOptions as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('correctAnswer') <p class="mt-1 text-[11px] text-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Poin Soal</label>
                        <input wire:model="points" type="number" min="1" max="100" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-base font-body-md text-body-md focus:ring-2 focus:ring-primary" />
                        @error('points') <p class="mt-1 text-[11px] text-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-sm pt-sm border-t border-outline-variant">
                    <button type="button" wire:click="closeQuestionModal" class="px-md py-sm border border-outline-variant rounded-lg font-label-md text-label-md text-on-surface-variant hover:bg-surface-container-high">
                        Batal
                    </button>
                    <button type="submit" class="px-md py-sm bg-primary text-on-primary rounded-lg font-label-md text-label-md active:scale-95 transition-all">
                        Simpan Soal
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endteleport
    @endif

</div>
