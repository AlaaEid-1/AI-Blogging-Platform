@extends('layouts.main')

@section('main-class', 'pt-24 pb-section-gap')

@section('title', $post['title'])

@push('style')
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    .article-column {
        max-width: 720px;
    }
</style>
@endpush


@section('content')
<article class="mx-auto article-column px-margin-mobile md:px-0">
    <!-- Headline -->
    <header class="mb-12">
        <h1 class="font-display-lg text-display-lg mb-8 text-on-surface">The Architecture of Silence: Designing
            for Focused Cognition</h1>
        <!-- Author Bio -->
        <div class="flex items-center justify-between py-6 border-y border-outline-variant">
            <div class="flex items-center gap-4">
                <img class="w-12 h-12 rounded-full grayscale"
                    data-alt="A close-up portrait of a thoughtful writer in a minimalist studio setting. The lighting is soft and directional, creating a gentle chiaroscuro effect. The image is rendered in a premium black and white style to match the editorial and high-contrast digital quiet aesthetic."
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCrB-fTH_sGc-EoJs3tiJjk17n12cNKJM223VhyTD5FfEtDknySO7GKIj0HvaJ3d-MoqtVOP8Yfk-dObjmX9mmt7mFiMRgqqpHWCsYFFpmpKBTaBXmgoB4M75gSnf4MJhP1WCx3DUb1E9iLnP1S039Q9dKb0JB_82yuO9S-WADZqyUPUVc_7lpe6Od7eVj2dcesczICWUxGQu7qeDZM0cH-Zqb8erGsQU-AEaICg0K2DynpHlKKOtRY0rPe9qhTIpUEN05vqmFz9_FG" />
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-ui-label text-ui-label font-bold text-on-surface">Julian Thorne</span>
                        <span class="text-secondary-fixed-dim">•</span>
                        <button
                            class="text-primary font-ui-label text-ui-label font-semibold hover:underline">Follow</button>
                    </div>
                    <p class="font-metadata text-metadata text-secondary">Oct 24, 2024 · 12 min read</p>
                </div>
            </div>
            <div class="flex gap-2">
                <button
                    class="material-symbols-outlined text-secondary hover:text-primary transition-colors">share</button>
                <button
                    class="material-symbols-outlined text-secondary hover:text-primary transition-colors">more_horiz</button>
            </div>
        </div>
    </header>
    <!-- Content -->
    <div class="space-y-8">
        <p class="font-body-lg text-body-lg text-on-surface leading-relaxed">
            In an era defined by the constant hum of notification-driven anxiety, the true luxury of the modern
            interface is not feature density, but intentional absence. We have spent decades filling every pixel
            with utility, forgetting that the primary purpose of reading is a solitary, quiet dialogue between
            the ink and the mind.
        </p>
        <h2 class="font-headline-md text-headline-md mt-12 mb-4 text-on-surface">The Psychology of White Space
        </h2>
        <p class="font-body-md text-body-md text-on-surface">
            When we strip away the secondary sidebars, the flashing banners, and the sticky social widgets, we
            allow the reader's cognitive load to reset. This isn't just a stylistic choice; it's a neurological
            necessity for deep comprehension. The grid must breathe.
        </p>
        <div class="my-12">
            <img class="w-full rounded-lg border border-outline-variant"
                data-alt="A stunning, minimalist architectural shot of a brightly lit gallery space with clean lines and vast open areas. The lighting is natural and airy, emphasizing the feeling of digital quiet and editorial focus. The palette is dominated by soft whites and sharp charcoal accents, reflecting a modern minimalist philosophy."
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCe9z5-CMxvCeCQThs7kHzXQ5geJGBesnpuQA7xMHfACS22Pxtkz4R7KK9r2bvlMMQw0dcote6RP0On5Tfiu4fPCTiAUZD7FMlSMV5mGUEbKYzWeebVMGq3fVli3vncJYSUj8lHI5od9K87xxH50MrEwLkFtOqVf7isIQFSMFZNyPWjKcLqU9cy4ueAsQnu3Q-sn7a3GaYWe-h3MpWxNwyaLxKSk3xhfxcVEi_H6xbFghZghxTOkCJnEq6APCaOSL0cc2jtB8-DzQ59" />
            <p class="font-metadata text-metadata text-center mt-4 text-secondary italic">Figure 1.1: The visual
                representation of cognitive breathing room in physical architecture.</p>
        </div>
        <blockquote class="pl-6 border-l-4 border-primary my-12 italic">
            <p class="font-body-lg text-body-lg text-on-surface">"True design is reached not when there is
                nothing left to add, but when there is nothing left to take away from the core message."</p>
        </blockquote>
        <h3 class="font-ui-label text-ui-label font-bold uppercase tracking-wider text-primary">Intentional
            Constraints</h3>
        <p class="font-body-md text-body-md text-on-surface">
            Standardizing column widths to 720px is more than a convention. It respects the physiological limits
            of the human eye, ensuring that the transition from the end of one line to the beginning of the next
            remains fluid and effortless. Any wider, and the brain begins to work too hard just to track the
            sequence.
        </p>
        <div class="p-8 bg-surface-container rounded-lg border border-outline-variant my-12">
            <h4 class="font-ui-label text-ui-label font-bold mb-4">Key Takeaways for Designers</h4>
            <ul class="space-y-3 font-body-md text-body-md text-on-surface">
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                    Prioritize monochromatic foundations for reading areas.
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                    Use 8px-based spacing for a rigorous vertical rhythm.
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                    Introduce a single high-energy digital accent (e.g., Electric Violet).
                </li>
            </ul>
        </div>
    </div>
</article>
@endsection