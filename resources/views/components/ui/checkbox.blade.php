@props([
    'disabled' => false,
    'className' => '',
])

@php
    $baseClasses = 'w-4 h-4 text-primary bg-surface border-border/60 rounded focus:ring-primary focus:ring-2 cursor-pointer transition-all shadow-sm';
    
    if ($disabled) {
        $classes = "{$baseClasses} opacity-50 cursor-not-allowed {$className}";
    } else {
        $classes = "{$baseClasses} {$className}";
    }
@endphp

<input type="checkbox" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>
