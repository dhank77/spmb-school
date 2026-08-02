<div class="flex-1 flex flex-col overflow-hidden">
    {{-- Header Slot --}}
    <x-slot:header>
        <h2 class="font-headline-md text-headline-md text-on-surface">Pipeline Verification</h2>
        <div class="flex items-center gap-xs px-sm py-1 bg-secondary-container/30 text-secondary rounded-full font-label-sm text-label-sm">
            <span class="material-symbols-outlined text-[16px]">info</span>
            <span>{{ $totalApplicants }} Total Applicants</span>
        </div>
    </x-slot:header>

    {{-- Actions Slot --}}
    <x-slot:actions>
        <div class="relative">
            <span class="absolute inset-y-0 left-3 flex items-center text-on-surface-variant">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </span>
            <input wire:model.live.debounce.300ms="search"
                   class="pl-10 pr-4 py-2 bg-surface border border-outline-variant rounded-lg text-body-md font-body-md focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all w-64"
                   placeholder="Search applicant..."
                   type="text" />
        </div>
    </x-slot:actions>

    {{-- Kanban Board Canvas --}}
    <div class="flex-1 overflow-x-auto p-gutter custom-scrollbar">
        <div class="flex gap-gutter h-full min-w-max">

            @foreach ($columns as $column)
                @php
                    $students = $studentsByStatus[$column['key']] ?? collect();
                @endphp

                <div class="kanban-column flex flex-col h-full bg-surface-container-low/50 rounded-xl p-sm border border-outline-variant/30">
                    {{-- Column Header --}}
                    <div class="flex justify-between items-center mb-md px-xs">
                        <h3 class="font-label-md text-label-md font-bold text-on-surface-variant flex items-center gap-xs">
                            <span class="w-2 h-2 rounded-full {{ $column['color'] }}"></span>
                            {{ $column['label'] }}
                            <span class="ml-2 text-label-sm font-normal text-on-surface-variant/60">{{ $students->count() }}</span>
                        </h3>
                        <button class="text-on-surface-variant hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[18px]">more_horiz</span>
                        </button>
                    </div>

                    {{-- Cards Container with Drag & Drop --}}
                    <div class="flex-1 overflow-y-auto space-y-sm custom-scrollbar pr-xs"
                         wire:sortable="sort"
                         wire:sortable.options="{ group: 'pipeline', animation: 150 }"
                         data-status="{{ $column['key'] }}"
                         x-data
                         x-on:sortable-end.window="
                             const evt = $event.detail;
                             if (evt.el && evt.el.dataset.status) {
                                 const itemId = evt.item.getAttribute('wire:sortable.item');
                                 if (itemId) {
                                     $wire.updateStatus(parseInt(itemId), evt.el.dataset.status);
                                 }
                             }
                         ">

                        @forelse ($students as $student)
                            <x-kanban-card :user="$student" :status="$column['key']" />
                        @empty
                            <div class="flex flex-col items-center justify-center py-lg text-on-surface-variant/50">
                                <span class="material-symbols-outlined text-[32px] mb-2">inbox</span>
                                <p class="text-label-sm text-center">No applicants</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    {{-- Footer Stats --}}
    <footer class="mt-auto px-gutter py-sm flex justify-between items-center bg-surface-container-lowest border-t border-outline-variant flex-shrink-0">
        <div class="flex gap-lg">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant">Processing Speed</span>
                <span class="font-label-md text-label-md font-bold text-primary">2.4m avg / card</span>
            </div>
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant">Today's Goal</span>
                <span class="font-label-md text-label-md font-bold text-secondary">
                    {{ $studentsByStatus['terverifikasi']->count() }} / {{ $totalApplicants }} Verified
                </span>
            </div>
        </div>
        <div class="flex items-center gap-sm">
            <p class="font-label-sm text-label-sm text-on-surface-variant">© {{ date('Y') }} Hitech School Admission System.</p>
        </div>
    </footer>

    {{-- ================================== --}}
    {{-- Student Detail Modal (Flux Modal)  --}}
    {{-- ================================== --}}
    <flux:modal wire:model="showStudentDetail" class="w-full max-w-2xl">

        @if ($selectedStudent)
            {{-- Modal Header --}}
            <div class="flex items-center gap-sm mb-lg">
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold text-headline-sm flex-shrink-0">
                    {{ $selectedStudent->initials() }}
                </div>
                <div>
                    <flux:heading size="lg">{{ $selectedStudent->name }}</flux:heading>
                    <flux:subheading>{{ $selectedStudent->registration_number ?? 'No Reg #' }}</flux:subheading>
                </div>
            </div>

            {{-- Status Badge --}}
            @php
                $statusConfig = [
                    'tersimpan'           => ['label' => 'Tersimpan',           'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',     'icon' => 'save'],
                    'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-secondary-container/30 text-secondary border-secondary-container/50',       'icon' => 'hourglass_empty'],
                    'terverifikasi'       => ['label' => 'Terverifikasi',       'class' => 'bg-primary/10 text-primary border-primary/20',                                  'icon' => 'verified_user'],
                    'ditolak'             => ['label' => 'Ditolak',             'class' => 'bg-error-container/20 text-error border-error-container/40',                   'icon' => 'cancel'],
                ];
                $sc = $statusConfig[$selectedStudent->verification_status] ?? $statusConfig['tersimpan'];
            @endphp
            <div class="flex items-center gap-xs px-sm py-xs border rounded-lg {{ $sc['class'] }} w-fit mb-lg">
                <span class="material-symbols-outlined text-[16px]">{{ $sc['icon'] }}</span>
                <span class="font-label-md text-label-md font-bold">{{ $sc['label'] }}</span>
            </div>

            <div class="space-y-md">

                {{-- Personal Info --}}
                <div class="bg-surface-container-lowest rounded-xl p-md border border-outline-variant/50 space-y-sm">
                    <h4 class="font-label-md text-label-md font-bold text-on-surface-variant uppercase tracking-wide flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[16px] text-primary">person</span>
                        Data Pribadi
                    </h4>
                    <div class="grid grid-cols-2 gap-sm text-body-sm font-body-sm">
                        <div>
                            <p class="text-on-surface-variant text-[11px] font-semibold uppercase tracking-wide mb-0.5">Email</p>
                            <p class="text-on-surface font-medium">{{ $selectedStudent->email }}</p>
                        </div>
                        <div>
                            <p class="text-on-surface-variant text-[11px] font-semibold uppercase tracking-wide mb-0.5">WhatsApp</p>
                            <p class="text-on-surface font-medium">{{ $selectedStudent->whatsapp_number ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-on-surface-variant text-[11px] font-semibold uppercase tracking-wide mb-0.5">NISN</p>
                            <p class="text-on-surface font-medium font-mono">{{ $selectedStudent->nisn ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-on-surface-variant text-[11px] font-semibold uppercase tracking-wide mb-0.5">NIK</p>
                            <p class="text-on-surface font-medium font-mono">{{ $selectedStudent->nik ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-on-surface-variant text-[11px] font-semibold uppercase tracking-wide mb-0.5">Tempat Lahir</p>
                            <p class="text-on-surface font-medium">{{ $selectedStudent->birth_place ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-on-surface-variant text-[11px] font-semibold uppercase tracking-wide mb-0.5">Tanggal Lahir</p>
                            <p class="text-on-surface font-medium">
                                {{ $selectedStudent->birth_date ? \Carbon\Carbon::parse($selectedStudent->birth_date)->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-on-surface-variant text-[11px] font-semibold uppercase tracking-wide mb-0.5">Jenis Kelamin</p>
                            <p class="text-on-surface font-medium capitalize">{{ $selectedStudent->gender ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-on-surface-variant text-[11px] font-semibold uppercase tracking-wide mb-0.5">Program</p>
                            <p class="text-on-surface font-medium">{{ $selectedStudent->program ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Academic Info --}}
                <div class="bg-surface-container-lowest rounded-xl p-md border border-outline-variant/50 space-y-sm">
                    <h4 class="font-label-md text-label-md font-bold text-on-surface-variant uppercase tracking-wide flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[16px] text-primary">school</span>
                        Data Akademik
                    </h4>
                    <div class="grid grid-cols-2 gap-sm text-body-sm font-body-sm">
                        <div>
                            <p class="text-on-surface-variant text-[11px] font-semibold uppercase tracking-wide mb-0.5">Asal Sekolah</p>
                            <p class="text-on-surface font-medium">{{ $selectedStudent->previous_school ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-on-surface-variant text-[11px] font-semibold uppercase tracking-wide mb-0.5">Tahun Lulus</p>
                            <p class="text-on-surface font-medium">{{ $selectedStudent->graduation_year ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Uploaded Documents --}}
                <div class="bg-surface-container-lowest rounded-xl p-md border border-outline-variant/50 space-y-sm">
                    <h4 class="font-label-md text-label-md font-bold text-on-surface-variant uppercase tracking-wide flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[16px] text-primary">folder_open</span>
                        Dokumen Upload
                    </h4>
                    <div class="space-y-sm">
                        {{-- KTP --}}
                        <div class="flex items-center justify-between p-sm rounded-lg border {{ $selectedStudent->document_identity ? 'border-primary/20 bg-primary/5' : 'border-outline-variant/50 bg-surface-container' }}">
                            <div class="flex items-center gap-sm">
                                <span class="material-symbols-outlined text-[24px] {{ $selectedStudent->document_identity ? 'text-primary' : 'text-on-surface-variant/40' }}"
                                      style="{{ $selectedStudent->document_identity ? 'font-variation-settings: \'FILL\' 1;' : '' }}">
                                    badge
                                </span>
                                <div>
                                    <p class="font-label-md text-label-md font-bold text-on-surface">Kartu Identitas (KTP/KK)</p>
                                    <p class="font-label-sm text-label-sm text-on-surface-variant">
                                        {{ $selectedStudent->document_identity ? 'Sudah diupload' : 'Belum diupload' }}
                                    </p>
                                </div>
                            </div>
                            @if ($selectedStudent->document_identity)
                                <a href="{{ asset('storage/' . $selectedStudent->document_identity) }}"
                                   target="_blank"
                                   class="flex items-center gap-1 px-sm py-xs bg-primary text-on-primary rounded-lg font-label-sm text-label-sm hover:bg-primary/90 transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                    Lihat
                                </a>
                            @else
                                <span class="px-sm py-xs bg-error-container/20 text-error rounded-lg font-label-sm text-label-sm border border-error-container/40">Tidak ada</span>
                            @endif
                        </div>

                        {{-- Ijazah --}}
                        <div class="flex items-center justify-between p-sm rounded-lg border {{ $selectedStudent->document_diploma ? 'border-primary/20 bg-primary/5' : 'border-outline-variant/50 bg-surface-container' }}">
                            <div class="flex items-center gap-sm">
                                <span class="material-symbols-outlined text-[24px] {{ $selectedStudent->document_diploma ? 'text-primary' : 'text-on-surface-variant/40' }}"
                                      style="{{ $selectedStudent->document_diploma ? 'font-variation-settings: \'FILL\' 1;' : '' }}">
                                    description
                                </span>
                                <div>
                                    <p class="font-label-md text-label-md font-bold text-on-surface">Ijazah / SKHUN</p>
                                    <p class="font-label-sm text-label-sm text-on-surface-variant">
                                        {{ $selectedStudent->document_diploma ? 'Sudah diupload' : 'Belum diupload' }}
                                    </p>
                                </div>
                            </div>
                            @if ($selectedStudent->document_diploma)
                                <a href="{{ asset('storage/' . $selectedStudent->document_diploma) }}"
                                   target="_blank"
                                   class="flex items-center gap-1 px-sm py-xs bg-primary text-on-primary rounded-lg font-label-sm text-label-sm hover:bg-primary/90 transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                    Lihat
                                </a>
                            @else
                                <span class="px-sm py-xs bg-error-container/20 text-error rounded-lg font-label-sm text-label-sm border border-error-container/40">Tidak ada</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Verification Action --}}
                <div class="bg-surface-container-lowest rounded-xl p-md border border-outline-variant/50 space-y-sm">
                    <h4 class="font-label-md text-label-md font-bold text-on-surface-variant uppercase tracking-wide flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[16px] text-primary">fact_check</span>
                        Ubah Status Verifikasi
                    </h4>
                    <div>
                        <label class="font-label-sm text-label-sm text-on-surface-variant mb-xs block">Catatan (opsional)</label>
                        <textarea wire:model="verificationNotes"
                                  rows="2"
                                  class="w-full px-sm py-xs bg-surface border border-outline-variant rounded-lg text-body-sm font-body-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all resize-none"
                                  placeholder="Tulis catatan verifikasi..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-xs">
                        <flux:button wire:click="changeVerificationStatus('terverifikasi')" variant="primary" class="col-span-1">
                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                            Verifikasi
                        </flux:button>
                        <flux:button wire:click="changeVerificationStatus('ditolak')" variant="danger" class="col-span-1">
                            <span class="material-symbols-outlined text-[16px]">cancel</span>
                            Tolak
                        </flux:button>
                        <flux:button wire:click="changeVerificationStatus('menunggu_verifikasi')" class="col-span-2">
                            <span class="material-symbols-outlined text-[16px]">hourglass_empty</span>
                            Kembalikan ke Menunggu Verifikasi
                        </flux:button>
                    </div>
                </div>

            </div>{{-- end space-y-md --}}

            <div class="flex justify-end mt-lg">
                <flux:modal.close>
                    <flux:button wire:click="closeStudent">Tutup</flux:button>
                </flux:modal.close>
            </div>
        @endif

    </flux:modal>

</div>

