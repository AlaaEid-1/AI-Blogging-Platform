@props(['trigger'])

<div x-data="{ open: false }" @click.away="open = false" class="relative inline-block text-left">
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-xl bg-surface/90 backdrop-blur-md shadow-lg ring-1 ring-border/50 focus:outline-none overflow-hidden"
         style="display: none;">
        <div class="py-1">
            {{ $slot }}
        </div>
    </div>
</div>
