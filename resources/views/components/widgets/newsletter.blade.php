@props([
'title' => 'The Sunday Edition',
])

<!-- Newsletter Sign Up -->
<div class="p-6 bg-primary-container rounded-xl text-on-primary space-y-4">
    <h3 class="font-headline-md text-[20px]">{{ $title }}</h3>
    <p class="font-metadata text-metadata text-on-primary-container">Join 40,000+ creators receiving our
        weekly digest on design, code, and intentional living.</p>
    <div class="space-y-2">
        {{ $slot }}
        <input
            class="w-full px-4 py-2 rounded bg-white/10 border border-white/20 text-white placeholder:text-white/60 focus:ring-1 focus:ring-white focus:outline-none"
            placeholder="email@example.com" type="email" />
        <button
            class="w-full py-2 bg-white text-primary font-ui-button text-ui-button rounded hover:bg-opacity-90 transition-all">Subscribe</button>
    </div>
    {{ $helper ?? '' }}
</div>