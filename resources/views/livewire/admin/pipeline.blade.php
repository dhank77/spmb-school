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
</div>
