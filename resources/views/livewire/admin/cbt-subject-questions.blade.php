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
        <div class="flex items-center gap-sm">
            {{-- AI Generate Button --}}
            <button
                wire:click="openAiModal"
                class="relative flex items-center gap-xs px-md py-sm rounded-lg font-label-md text-label-md active:scale-95 transition-all shadow-sm overflow-hidden group"
                style="background: linear-gradient(135deg, #7c3aed, #4f46e5, #0ea5e9); color: #fff;"
            >
                <span class="absolute inset-0 opacity-0 group-hover:opacity-20 transition-opacity" style="background: white;"></span>
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
                Generate Soal AI
            </button>

            {{-- Manual Add Button --}}
            <button
                wire:click="openQuestionModal"
                class="flex items-center gap-xs px-md py-sm bg-primary text-on-primary rounded-lg font-label-md text-label-md active:scale-95 transition-all shadow-sm"
            >
                <span class="material-symbols-outlined text-[20px]">add</span>
                Tambah Soal Baru
            </button>
        </div>
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

    {{-- AI Question Generation Modal --}}
    @if($showAiModal)
    @teleport('body')
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center p-md">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-2xl w-full max-w-[560px] overflow-hidden">

            {{-- Modal Header --}}
            <div class="p-md border-b border-outline-variant flex justify-between items-center" style="background: linear-gradient(135deg, #7c3aed15, #0ea5e915);">
                <div class="flex items-center gap-sm">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #7c3aed, #0ea5e9);">
                        <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
                    </div>
                    <div>
                        <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">Generate Soal dengan AI</h3>
                        <p class="font-label-sm text-label-sm text-on-surface-variant">Powered by SumoPod AI &mdash; {{ $subject->name }}</p>
                    </div>
                </div>
                <button wire:click="closeAiModal" class="text-on-surface-variant hover:text-on-surface transition-colors" :disabled="$wire.isGeneratingAi">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <form wire:submit="generateAiQuestions" class="p-md space-y-md">

                {{-- Error Message --}}
                @if($aiErrorMessage)
                <div class="flex items-start gap-sm p-sm rounded-lg bg-error/10 border border-error/30 text-error font-body-sm text-body-sm">
                    <span class="material-symbols-outlined text-[18px] flex-shrink-0 mt-0.5">error</span>
                    <span>{{ $aiErrorMessage }}</span>
                </div>
                @endif

                {{-- Topic --}}
                <div>
                    <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">
                        Topik / Materi Soal <span class="text-error">*</span>
                    </label>
                    <input
                        wire:model="aiTopic"
                        type="text"
                        placeholder="Contoh: Persamaan Linear, Fotosintesis, Revolusi Perancis..."
                        class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-sm font-body-md text-body-md focus:ring-2 focus:ring-primary outline-none transition-shadow"
                        wire:loading.attr="disabled"
                        wire:target="generateAiQuestions"
                    />
                    @error('aiTopic') <p class="mt-1 text-[11px] text-error">{{ $message }}</p> @enderror
                </div>

                {{-- Count & Points --}}
                <div class="grid grid-cols-2 gap-sm">
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">
                            Jumlah Soal <span class="text-error">*</span>
                        </label>
                        <input
                            wire:model="aiQuestionCount"
                            type="number"
                            min="1"
                            max="20"
                            placeholder="1 – 20"
                            class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-sm font-body-md text-body-md focus:ring-2 focus:ring-primary outline-none transition-shadow"
                            wire:loading.attr="disabled"
                            wire:target="generateAiQuestions"
                        />
                        @error('aiQuestionCount') <p class="mt-1 text-[11px] text-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">
                            Poin per Soal <span class="text-error">*</span>
                        </label>
                        <input
                            wire:model="aiPoints"
                            type="number"
                            min="1"
                            max="100"
                            placeholder="1 – 100"
                            class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-sm font-body-md text-body-md focus:ring-2 focus:ring-primary outline-none transition-shadow"
                            wire:loading.attr="disabled"
                            wire:target="generateAiQuestions"
                        />
                        @error('aiPoints') <p class="mt-1 text-[11px] text-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Custom Instruction --}}
                <div>
                    <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">
                        Instruksi Tambahan
                        <span class="text-on-surface-variant/50 font-normal">(opsional)</span>
                    </label>
                    <textarea
                        wire:model="aiCustomInstruction"
                        rows="2"
                        placeholder="Contoh: Fokus pada rumus trigonometri, gunakan konteks kehidupan sehari-hari..."
                        class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-sm font-body-md text-body-md focus:ring-2 focus:ring-primary outline-none resize-none transition-shadow"
                        wire:loading.attr="disabled"
                        wire:target="generateAiQuestions"
                    ></textarea>
                    @error('aiCustomInstruction') <p class="mt-1 text-[11px] text-error">{{ $message }}</p> @enderror
                </div>

                {{-- Loading state — shown via wire:loading (client-side, instant) --}}
                <div
                    wire:loading
                    wire:target="generateAiQuestions"
                    class="flex items-center gap-sm p-sm rounded-lg bg-primary/5 border border-primary/20"
                >
                    <div class="w-5 h-5 rounded-full border-2 border-primary border-t-transparent animate-spin flex-shrink-0"></div>
                    <span class="font-body-sm text-body-sm text-primary">
                        AI sedang membuat soal dalam Bahasa Indonesia, mohon tunggu...
                    </span>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-sm pt-sm border-t border-outline-variant">
                    <button
                        type="button"
                        wire:click="closeAiModal"
                        class="px-md py-sm border border-outline-variant rounded-lg font-label-md text-label-md text-on-surface-variant hover:bg-surface-container-high transition-colors"
                        wire:loading.attr="disabled"
                        wire:target="generateAiQuestions"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="relative flex items-center gap-xs px-md py-sm rounded-lg font-label-md text-label-md active:scale-95 transition-all overflow-hidden disabled:opacity-60 disabled:cursor-not-allowed"
                        style="background: linear-gradient(135deg, #7c3aed, #4f46e5, #0ea5e9); color: #fff;"
                        wire:loading.attr="disabled"
                        wire:target="generateAiQuestions"
                    >
                        {{-- Normal state --}}
                        <span wire:loading.remove wire:target="generateAiQuestions" class="flex items-center gap-xs">
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
                            Generate {{ $aiQuestionCount }} Soal
                        </span>
                        {{-- Loading state --}}
                        <span wire:loading wire:target="generateAiQuestions" class="flex items-center gap-xs">
                            <div class="w-4 h-4 rounded-full border-2 border-white border-t-transparent animate-spin"></div>
                            Membuat Soal...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endteleport
    @endif

</div>
