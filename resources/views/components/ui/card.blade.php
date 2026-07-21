@props([
    'className' => '',
    'glass' => false
])

@php
    $baseClasses = 'rounded-2xl border border-border shadow-sm overflow-hidden';
    
    $isGlass = filter_var($glass, FILTER_VALIDATE_BOOLEAN);
    if ($isGlass) {
        $classes = "{$baseClasses} glass {$className}";
    } else {
        $classes = "{$baseClasses} bg-surface hover:shadow-premium transition-shadow duration-300 {$className}";
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
