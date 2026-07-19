@props([
    'user',
    'status',
])

@php
    $borderColors = [
        'tersimpan' => 'border-outline',
        'menunggu_verifikasi' => 'border-secondary-container',
        'terverifikasi' => 'border-primary',
        'ditolak' => 'border-error',
    ];

    $borderColor = $borderColors[$status] ?? 'border-outline';
    $isRejected = $status === 'ditolak';
@endphp

<div class="kanban-card bg-surface-container-lowest border-t-4 {{ $borderColor }} rounded-xl p-sm shadow-sm border border-outline-variant/50 cursor-grab active:cursor-grabbing {{ $isRejected ? 'opacity-80 grayscale-[0.3]' : '' }}"
     wire:key="card-{{ $user->id }}"
     wire:sortable.item="{{ $user->id }}">

    <div class="flex items-center gap-sm mb-sm">
        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-label-md flex-shrink-0">
            {{ $user->initials() }}
        </div>
        <div class="min-w-0">
            <p class="font-label-md text-label-md font-bold text-on-surface truncate">{{ $user->name }}</p>
            <p class="font-label-sm text-label-sm text-on-surface-variant">{{ $user->registration_number ?? 'No Reg #' }}</p>
        </div>
    </div>

    @if ($status === 'tersimpan')
        {{-- Document progress indicator --}}
        <div class="space-y-xs">
            <div class="flex justify-between items-center text-label-sm text-on-surface-variant">
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">description</span>
                    Docs: {{ $user->documents_submitted }}/{{ $user->documents_total }}
                </span>
                <span class="text-primary font-bold">{{ $user->document_progress }}%</span>
            </div>
            <div class="w-full h-2 bg-secondary/10 rounded-full overflow-hidden">
                <div class="bg-tertiary h-full transition-all duration-1000" style="width: {{ $user->document_progress }}%;"></div>
            </div>
        </div>
        @if ($user->verification_notes)
            <div class="flex gap-xs mt-sm">
                <span class="px-2 py-0.5 bg-surface-container-high rounded text-[10px] font-bold text-on-surface-variant">{{ Str::upper($user->verification_notes) }}</span>
            </div>
        @endif
    @elseif ($status === 'menunggu_verifikasi')
        <div class="space-y-xs">
            <div class="flex justify-between items-center text-label-sm text-on-surface-variant">
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">history</span>
                    Submitted {{ $user->updated_at->diffForHumans() }}
                </span>
            </div>
            <div class="flex gap-xs mt-sm">
                <span class="px-2 py-0.5 bg-secondary-container/20 text-secondary rounded text-[10px] font-bold border border-secondary-container/40">READY FOR REVIEW</span>
            </div>
        </div>
    @elseif ($status === 'terverifikasi')
        <div class="flex items-center justify-between mt-sm">
            <span class="flex items-center gap-1 text-[10px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-full">
                <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                VERIFIED
            </span>
            <span class="material-symbols-outlined text-primary text-[18px]">verified_user</span>
        </div>
    @elseif ($status === 'ditolak')
        @if ($user->verification_notes)
            <div class="p-xs bg-error-container/20 rounded-lg mt-sm border border-error-container/40">
                <p class="text-[10px] text-error font-medium">{{ $user->verification_notes }}</p>
            </div>
        @endif
    @endif
</div>
