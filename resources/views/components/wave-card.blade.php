@props([
    'wave' => null,
])

@php
    $isActive = $wave->status === 'active';
    $isClosed = $wave->status === 'closed';
    $isUpcoming = $wave->status === 'upcoming';

    $cardClasses = match($wave->status) {
        'active' => 'bg-surface-container-lowest border-2 border-primary p-md rounded-3xl relative overflow-hidden shadow-xl transform scale-105 z-10',
        'closed' => 'bg-surface-container-lowest border border-outline-variant p-md rounded-3xl relative overflow-hidden shadow-sm group hover:shadow-md transition-shadow',
        'upcoming' => 'bg-surface-container-lowest border border-outline-variant p-md rounded-3xl relative overflow-hidden shadow-sm hover:shadow-md transition-shadow',
    };

    $badgeClasses = match($wave->status) {
        'active' => 'bg-secondary text-on-secondary text-[10px] uppercase font-bold px-sm py-1 rounded-full animate-pulse',
        'closed' => 'bg-error-container text-on-error-container text-[10px] uppercase font-bold px-sm py-1 rounded-full',
        'upcoming' => 'bg-surface-container-highest text-on-surface-variant text-[10px] uppercase font-bold px-sm py-1 rounded-full',
    };

    $badgeText = match($wave->status) {
        'active' => 'Aktif Sekarang',
        'closed' => 'Ditutup',
        'upcoming' => 'Mendatang',
    };

    $titleColor = $isActive ? 'text-primary' : 'text-on-surface';
    $quotaColor = match($wave->status) {
        'active' => 'text-secondary',
        'closed' => 'text-error',
        'upcoming' => 'text-on-surface',
    };

    $progressTrack = match($wave->status) {
        'active' => 'bg-secondary-container/20',
        default => 'bg-surface-container-highest',
    };

    $progressFill = match($wave->status) {
        'active' => 'bg-[#8ECA3C]',
        'closed' => 'bg-outline-variant',
        'upcoming' => 'bg-secondary-container',
    };
@endphp

<div class="{{ $cardClasses }}">
    <div class="absolute top-0 right-0 p-sm">
        <span class="{{ $badgeClasses }}">{{ $badgeText }}</span>
    </div>

    <h3 class="font-headline-sm text-headline-sm {{ $titleColor }} mb-xs">{{ $wave->name }}</h3>
    <p class="text-label-sm font-label-sm text-on-surface-variant mb-md">{{ $wave->period }}</p>

    <div class="space-y-sm mb-lg">
        <div class="flex justify-between items-center">
            <span class="text-label-md text-on-surface-variant">Biaya Pendaftaran</span>
            <span class="font-bold text-on-surface">{{ $wave->formatted_cost }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-label-md text-on-surface-variant">Kuota Tersisa</span>
            <span class="font-bold {{ $quotaColor }}">{{ $wave->remaining_quota }} Kursi</span>
        </div>
    </div>

    <div class="w-full h-2 {{ $progressTrack }} rounded-full overflow-hidden">
        <div class="h-full {{ $progressFill }}" style="width: {{ $wave->quota_percentage }}%"></div>
    </div>

    @if($isClosed)
        <button class="w-full mt-lg py-sm rounded-lg border border-outline-variant text-outline-variant cursor-not-allowed" disabled>Kapasitas Penuh</button>
    @elseif($isActive)
        @if(Route::has('register'))
            <a href="{{ route('register') }}" class="w-full mt-lg py-sm rounded-lg bg-primary text-on-primary font-bold hover:bg-surface-tint transition-colors block text-center">Daftar Sekarang</a>
        @else
            <button class="w-full mt-lg py-sm rounded-lg bg-primary text-on-primary font-bold hover:bg-surface-tint transition-colors">Daftar Sekarang</button>
        @endif
    @else
        <button class="w-full mt-lg py-sm rounded-lg border border-primary text-primary font-bold hover:bg-primary-fixed transition-colors">Beritahu Saya</button>
    @endif
</div>
