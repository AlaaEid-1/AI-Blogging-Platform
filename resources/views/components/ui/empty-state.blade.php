@props([
    'icon' => 'inbox',
    'title' => 'No content found',
    'description' => 'There is nothing to display here yet.',
    'action' => null,
    'actionUrl' => '#',
])

<div class="flex flex-col items-center justify-center p-12 text-center border border-dashed border-border/60 rounded-2xl bg-surface-secondary/30">
    <div class="w-16 h-16 mb-4 rounded-full bg-surface flex items-center justify-center shadow-sm border border-border/50">
        <span class="material-symbols-outlined text-text-tertiary text-[32px]" style="font-variation-settings: 'wght' 300;">
            {{ $icon }}
        </span>
    </div>
    <h3 class="text-lg font-semibold text-text-primary mb-2 tracking-tight">{{ $title }}</h3>
    <p class="text-text-secondary text-sm max-w-sm mb-6">{{ $description }}</p>
    
    @if($action)
        <x-ui.button type="link" href="{{ $actionUrl }}" variant="primary">
            {{ $action }}
        </x-ui.button>
    @endif
</div>
