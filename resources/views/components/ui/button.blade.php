@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'icon' => null,
    'className' => '',
    'type' => 'button'
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium transition-all duration-200 rounded-lg active:scale-95 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-2 focus:ring-offset-2';

    $variantClasses = [
        'primary' => 'bg-primary text-white hover:bg-primary-dark focus:ring-primary shadow-soft hover:shadow-premium',
        'secondary' => 'bg-surface hover:bg-surface-hover text-text-primary border border-border focus:ring-border',
        'ghost' => 'bg-transparent hover:bg-surface-hover text-text-secondary focus:ring-border',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-600 shadow-soft',
        'ai' => 'bg-gradient-to-r from-primary to-accent text-white shadow-soft hover:shadow-premium focus:ring-primary',
    ];

    $sizeClasses = [
        'sm' => 'text-sm px-3 py-1.5',
        'md' => 'text-sm px-4 py-2',
        'lg' => 'text-base px-6 py-3',
        'icon' => 'p-2',
    ];

    $safeVariant = $variantClasses[$variant] ?? $variantClasses['primary'];
    $safeSize = $sizeClasses[$size] ?? $sizeClasses['md'];
    $classes = "{$baseClasses} {$safeVariant} {$safeSize} {$className}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="material-symbols-outlined text-[1.2em]">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="material-symbols-outlined text-[1.2em]">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </button>
@endif
