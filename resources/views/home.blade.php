<x-layout.app title="Feed" :has-sidebar="true" :has-right-sidebar="true">
    <div class="max-w-2xl mx-auto pb-12">
    
    <!-- Header Area -->
    <div class="mb-10 hidden lg:block">
        <h1 class="text-3xl font-extrabold text-text-primary mb-2">Your Feed</h1>
        <p class="text-text-secondary text-base">Discover stories, thinking, and expertise from writers on any topic.</p>
    </div>

    <!-- Quick Actions -->
    <div class="glass backdrop-blur-xl bg-white/60 border border-border/40 shadow-xl shadow-surface-secondary/20 rounded-3xl p-6 mb-10 flex items-center gap-5">
        @auth
            <x-ui.avatar :src="auth()->user()->avatar_url" :alt="auth()->user()->name" size="lg" />
        @else
            <x-ui.avatar src="{{ asset('images/avatars/blank.png') }}" alt="Guest" size="lg" />
        @endauth
        <a href="{{ auth()->check() ? route('dashboard.posts.create') : route('login') }}" aria-label="Write a new post" class="flex-1 bg-surface-hover/80 hover:bg-white text-left text-text-tertiary hover:text-text-secondary px-5 py-3.5 rounded-full text-base font-medium transition-all duration-300 cursor-pointer border border-border/50 shadow-inner focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary inline-block">
            What's on your mind? Start writing...
        </a>
        <a href="{{ auth()->check() ? route('dashboard.posts.create') : route('login') }}" class="hidden sm:inline-flex items-center justify-center gap-2 px-5 py-3.5 border border-transparent text-sm font-bold rounded-full text-white bg-gradient-to-r from-primary to-accent hover:shadow-[0_8px_25px_-8px_rgba(99,102,241,0.5)] transition-all duration-300 hover:-translate-y-0.5 active:scale-95">
            <span class="material-symbols-outlined text-[18px]">edit_document</span>
            Write
        </a>
    </div>

    <!-- Feed Header -->
    <div class="flex items-center gap-6 border-b border-border/50 mb-8">
        <span class="pb-3 border-b-2 border-primary text-text-primary font-bold tracking-wide uppercase text-sm">Latest Posts</span>
    </div>

    <!-- Posts Feed -->
    <div class="flex flex-col gap-6">
        @forelse ($posts as $post)
            <x-post.card :post="$post" />
        @empty
            <div class="text-center py-16 bg-surface/50 rounded-3xl border border-border/40 shadow-inner">
                <span class="material-symbols-outlined text-5xl text-text-tertiary mb-4 opacity-50">article</span>
                <h3 class="text-xl font-bold text-text-primary mb-2">No posts yet</h3>
                <p class="text-text-secondary text-sm">Be the first to create an amazing post.</p>
            </div>
        @endforelse
    </div>

    </div>
</x-layout.app>
