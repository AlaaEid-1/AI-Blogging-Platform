@props([
    'disabled' => false,
    'className' => '',
    'error' => false,
])

@php
    $baseClasses = 'w-full px-4 py-2.5 bg-surface border rounded-xl focus:ring-2 outline-none transition-all placeholder:text-text-tertiary text-sm text-text-primary shadow-sm min-h-[100px] resize-y';
    
    if ($error) {
        $classes = "{$baseClasses} border-red-300 focus:ring-red-200 focus:border-red-500 {$className}";
    } else {
        $classes = "{$baseClasses} border-border/60 focus:ring-primary/20 focus:border-primary {$className}";
    }
@endphp

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>{{ $slot }}</textarea>
