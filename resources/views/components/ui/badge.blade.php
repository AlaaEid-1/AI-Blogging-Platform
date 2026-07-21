@props([
    'variant' => 'primary',
    'className' => ''
])

@php
    $baseClasses = 'inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap';

    $variantClasses = [
        'primary' => 'bg-primary/10 text-primary',
        'secondary' => 'bg-surface-hover text-text-secondary border border-border',
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'danger' => 'bg-red-100 text-red-800',
        'ai' => 'bg-gradient-to-r from-primary to-accent text-white shadow-soft',
    ];

    $safeVariant = $variantClasses[$variant] ?? $variantClasses['primary'];
    $classes = "{$baseClasses} {$safeVariant} {$className}";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
