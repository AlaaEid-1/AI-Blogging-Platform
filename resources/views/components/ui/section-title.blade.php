@props([
    'title',
    'description' => null,
    'icon' => null,
])

<div class="mb-6">
    <div class="flex items-center gap-3 mb-1">
        @if($icon)
            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
            </div>
        @endif
        <h2 class="text-xl font-bold text-text-primary tracking-tight">{{ $title }}</h2>
    </div>
    @if($description)
        <p class="text-text-secondary text-sm {{ $icon ? 'ml-11' : '' }}">{{ $description }}</p>
    @endif
</div>
