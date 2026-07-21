@props([
    'size' => 'md',
    'text' => null,
    'fullScreen' => false
])

@php
    $sizeClasses = match($size) {
        'sm' => 'w-4 h-4 border-2',
        'lg' => 'w-12 h-12 border-4',
        default => 'w-8 h-8 border-3',
    };
@endphp

<div class="flex flex-col items-center justify-center {{ $fullScreen ? 'min-h-[50vh]' : 'p-4' }}">
    <div class="relative">
        <div class="{{ $sizeClasses }} border-surface-secondary rounded-full"></div>
        <div class="{{ $sizeClasses }} border-primary border-t-transparent rounded-full animate-spin absolute inset-0"></div>
    </div>
    @if($text)
        <p class="mt-4 text-sm font-medium text-text-secondary animate-pulse">{{ $text }}</p>
    @endif
</div>
