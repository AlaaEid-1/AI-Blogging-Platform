<x-layout.app title="Search — {{ config('app.name') }}">
    <div class="max-w-2xl mx-auto pb-12">

        {{-- Header --}}
        <div class="mb-8 hidden lg:block">
            <h1 class="text-3xl font-extrabold text-text-primary mb-2">Search</h1>
            <p class="text-text-secondary text-base">Find stories and ideas across every topic.</p>
        </div>

        {{-- Search Form --}}
        <form method="GET" action="{{ route('search') }}" class="mb-8">
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-text-tertiary group-focus-within:text-primary transition-colors">search</span>
                <input
                    id="search-query"
                    type="text"
                    name="query"
                    value="{{ $query ?? '' }}"
                    placeholder="Search posts..."
                    autofocus
                    class="w-full bg-surface border border-border rounded-full py-3 pl-12 pr-6 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm transition-all text-text-primary placeholder:text-text-tertiary"
                >
            </div>
        </form>

        {{-- Results --}}
        @if($query)
            <p class="text-sm text-text-secondary mb-6">
                Results for <span class="font-semibold text-text-primary">"{{ $query }}"</span>
            </p>
        @endif

        <div class="flex flex-col gap-6">
            @forelse ($posts as $post)
                <x-post.card :post="$post" />
            @empty
                <div class="text-center py-16 bg-surface/50 rounded-3xl border border-border/40 shadow-inner">
                    <span class="material-symbols-outlined text-5xl text-text-tertiary mb-4 opacity-50">search_off</span>
                    <h3 class="text-xl font-bold text-text-primary mb-2">
                        {{ $query ? 'No results found' : 'Start searching' }}
                    </h3>
                    <p class="text-text-secondary text-sm">
                        {{ $query ? 'Try different keywords or browse the latest posts.' : 'Enter a keyword above to find posts.' }}
                    </p>
                </div>
            @endforelse

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>

    </div>
</x-layout.app>
