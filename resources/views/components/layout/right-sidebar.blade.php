<aside class="hidden xl:flex flex-col w-80 h-screen overflow-y-auto py-6 pl-6 pr-4 shrink-0 z-40 bg-background/50 backdrop-blur-md border-l border-border/50 no-scrollbar">

    <!-- Search Bar -->
    <div class="relative mb-8 group">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-text-tertiary group-focus-within:text-primary transition-colors">search</span>
        <input type="text" placeholder="Search..." class="w-full bg-surface border border-border rounded-full py-3 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm transition-all text-text-primary placeholder:text-text-tertiary">
    </div>

    <div class="space-y-10">
        <!-- Trending -->
        {{-- <div>
            <h3 class="font-bold text-text-primary mb-4 text-base">Trending on {{ config('app.name', 'Write AI') }}</h3>
            <div class="space-y-5">
                <!-- Trending Item 1 -->
                <div class="group">
                    <div class="flex items-center gap-2 mb-1">
                        <x-ui.avatar src="https://ui-avatars.com/api/?name=Alex+Rivera&background=6366f1&color=fff" alt="Alex Rivera" size="xs" />
                        <span class="text-xs font-medium text-text-secondary">Alex Rivera</span>
                    </div>
                    <h4 class="font-bold text-text-primary group-hover:text-primary transition-colors text-sm line-clamp-2 leading-snug">The Future of AI in Creative Writing</h4>
                </div>

                <!-- Trending Item 2 -->
                <div class="group">
                    <div class="flex items-center gap-2 mb-1">
                        <x-ui.avatar src="https://ui-avatars.com/api/?name=Sarah+Chen&background=10b981&color=fff" alt="Sarah Chen" size="xs" />
                        <span class="text-xs font-medium text-text-secondary">Sarah Chen</span>
                    </div>
                    <h4 class="font-bold text-text-primary group-hover:text-primary transition-colors text-sm line-clamp-2 leading-snug">10 Best Practices for Building SaaS Products in 2024</h4>
                </div>

                <!-- Trending Item 3 -->
                <div class="group">
                    <div class="flex items-center gap-2 mb-1">
                        <x-ui.avatar src="https://ui-avatars.com/api/?name=Marcus+Johnson&background=f59e0b&color=fff" alt="Marcus Johnson" size="xs" />
                        <span class="text-xs font-medium text-text-secondary">Marcus Johnson</span>
                    </div>
                    <h4 class="font-bold text-text-primary group-hover:text-primary transition-colors text-sm line-clamp-2 leading-snug">Why Tailwind CSS Changed How I Design</h4>
                </div>
            </div>

            <span class="block text-primary text-sm mt-4 hover:underline cursor-pointer">Show more</span>
        </div> --}}

        <hr class="border-border/50">

        <!-- Popular Authors -->
        <div>
            <h3 class="font-bold text-text-primary mb-4 text-base">Popular Authors</h3>
            <div class="space-y-4">
                @php
                    $popularAuthors = \App\Models\User::has('posts')
                        ->withCount('posts')
                        ->orderByDesc('posts_count')
                        ->take(4)
                        ->get();
                @endphp
                @foreach($popularAuthors as $author)
                <div class="flex items-center justify-between gap-3 group">
                    <a href="{{ route('users.show', $author->username) }}" class="flex items-center gap-3 overflow-hidden flex-1">
                        <x-ui.avatar :src="$author->avatar_url ?? asset('images/avatars/blank.png')" :alt="$author->name" size="sm" />
                        <div class="truncate">
                            <h4 class="font-bold text-sm text-text-primary truncate group-hover:text-primary transition-colors">{{ $author->name }}</h4>
                            <p class="text-xs text-text-tertiary truncate">{{ $author->posts_count }} published</p>
                        </div>
                    </a>
                    @if(auth()->check() && auth()->id() !== $author->id)
                        @php
                            $isFollowing = auth()->user()->followings()->where('user_id', $author->id)->exists();
                        @endphp
                        @if($isFollowing)
                            <form action="{{ route('users.unfollow', $author->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-text-secondary bg-surface border border-border px-3 py-1.5 rounded-full hover:bg-surface-secondary transition-colors">
                                    Following
                                </button>
                            </form>
                        @else
                            <form action="{{ route('users.follow', $author->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-white bg-primary px-3 py-1.5 rounded-full hover:bg-primary-hover transition-colors">
                                    Follow
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Footer Links -->
        <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs text-text-tertiary pb-8">
            <span class="hover:underline cursor-pointer">About</span>
            <span class="hover:underline cursor-pointer">Accessibility</span>
            <span class="hover:underline cursor-pointer">Help Center</span>
            <span class="hover:underline cursor-pointer">Privacy & Terms</span>
            <span class="hover:underline cursor-pointer">Advertise</span>
            <span class="mt-2 w-full">&copy; {{ date('Y') }} {{ config('app.name', 'Write AI') }}</span>
        </div>
    </div>
</aside>
