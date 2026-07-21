@props([
    'src' => asset('images/avatars/blank.png'),
    'alt' => 'Avatar',
    'size' => 'md',
    'className' => ''
])

@php
    $sizeClasses = [
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-14 h-14',
        'xl' => 'w-24 h-24'
    ];
    $classes = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="{{ $classes }} rounded-full overflow-hidden border-2 border-surface flex-shrink-0 {{ $className }}">
    <img src="{{ $src }}" alt="{{ $alt }}" class="w-full h-full object-cover">
</div>
