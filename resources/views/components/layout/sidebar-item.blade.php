@props([
    'href',
    'icon',
    'active' => false,
    'badge' => null
])

@php
    $baseClasses = 'flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 group relative font-medium text-sm';
    
    if ($active) {
        $classes = "{$baseClasses} bg-primary text-white shadow-soft";
        $iconStyle = "font-variation-settings: 'FILL' 1;";
    } else {
        $classes = "{$baseClasses} text-text-secondary hover:bg-surface-hover hover:text-text-primary";
        $iconStyle = "font-variation-settings: 'FILL' 0;";
    }
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    <span class="material-symbols-outlined text-[24px] group-hover:scale-110 transition-transform duration-200" style="{{ $iconStyle }}">
        {{ $icon }}
    </span>
    <span class="flex-1">{{ $slot }}</span>
    
    @if($badge)
        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full absolute right-4">
            {{ $badge }}
        </span>
    @endif
</a>
