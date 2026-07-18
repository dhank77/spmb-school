@props([
    'question' => '',
    'answer' => '',
])

<div x-data="{ open: false }" class="faq-item">
    <button
        @click="open = !open"
        class="w-full flex justify-between items-center p-[var(--spacing-md)] bg-white border border-outline-variant rounded-2xl text-left hover:border-primary transition-all duration-300"
        :class="{ 'border-primary': open }"
        aria-expanded="false"
        :aria-expanded="open.toString()"
    >
        <span class="font-headline-sm text-headline-sm text-on-surface">{{ $question }}</span>
        <span
            class="material-symbols-outlined transition-transform duration-300"
            :class="{ 'rotate-180': open }"
        >expand_more</span>
    </button>
    <div
        x-show="open"
        x-collapse
        class="p-[var(--spacing-md)] text-on-surface-variant bg-surface-container-low rounded-b-2xl -mt-4 border-x border-b border-outline-variant"
    >
        {{ $answer }}
    </div>
</div>
