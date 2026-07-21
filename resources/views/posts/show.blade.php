<x-layout.app :title="$post->title" :has-sidebar="true" :has-right-sidebar="false">
    <div class="max-w-3xl mx-auto pb-24 xl:pb-32">
    
    <article class="bg-surface rounded-3xl border border-border/40 shadow-xl shadow-surface-secondary/20 overflow-hidden mb-12 relative group">
        <!-- Cover Image -->
        @if($post->cover_image)
            <div class="w-full h-[300px] sm:h-[400px] overflow-hidden bg-surface-hover relative">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent z-10 pointer-events-none"></div>
                <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
            </div>
        @endif

        <div class="p-8 sm:p-12 relative z-20">
            <!-- Author & Metadata -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 mb-10 pb-8 border-b border-border/50">
                <div class="flex items-center gap-4">
                    <x-ui.avatar :src="$post->user?->avatar_url ?? asset('images/avatars/blank.png')" :alt="$post->user?->name ?? 'Unknown User'" size="lg" className="ring-4 ring-surface shadow-md" />
                    <div>
                        <div class="flex items-center gap-1.5 mb-1">
                            <a href="{{ route('users.show', $post->user?->username ?? 'unknown') }}" class="font-bold text-text-primary text-lg hover:underline">{{ $post->user?->name ?? 'Unknown User' }}</a>
                            @if($post->user?->hasAbility('verified') ?? false)
                                <span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                            @endif
                        </div>
                        <div class="text-sm font-medium text-text-tertiary flex items-center gap-2">
                            <span>{{ $post->publish_time ? $post->publish_time->format('M d, Y') : now()->format('M d, Y') }}</span>
                            <span class="text-border/80">&bull;</span>
                            <span>{{ $post->read_time }} min read</span>
                            <span class="text-border/80">&bull;</span>
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">visibility</span> {{ number_format($post->views) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('users.show', $post->user?->username ?? 'unknown') }}" class="flex items-center justify-center gap-2 px-4 py-2 border border-border/60 text-sm font-bold rounded-xl text-text-secondary bg-surface hover:bg-surface-hover hover:text-text-primary transition-colors active:scale-95 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">person</span> View Profile
                    </a>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-4xl sm:text-5xl font-extrabold text-text-primary leading-tight mb-8 tracking-tight">
                {{ $post->title }}
            </h1>

            <!-- Tags -->
            @if($post->tags && $post->tags->count() > 0)
                <div class="flex flex-wrap gap-2 mb-10">
                    @foreach($post->tags as $tag)
                        <span class="px-3 py-1 bg-surface-hover border border-border/50 rounded-full text-xs font-bold text-text-secondary">#{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif

            <!-- Content -->
            <div class="prose prose-lg prose-purple max-w-none text-text-secondary leading-relaxed">
                {!! $post->content !!}
            </div>
        </div>
    </article>

    <div class="fixed bottom-10 left-1/2 -translate-x-1/2 z-40 w-max">
        <div class="flex items-center gap-2 sm:gap-6 px-4 sm:px-8 py-3.5 glass backdrop-blur-2xl bg-white/70 border border-white/50 rounded-full shadow-2xl shadow-primary/10">
            @auth
            <form action="{{ route('favorites.toggle', $post->id) }}" method="POST" class="inline">
                @csrf
                @php $hasFavorited = auth()->user()->favorites()->where('post_id', $post->id)->exists(); @endphp
                <button aria-label="Favorite post" class="flex items-center gap-2 focus:outline-none rounded-full p-2 group transition-all">
                    <span class="material-symbols-outlined transition-colors {{ $hasFavorited ? 'text-red-500' : 'text-text-secondary group-hover:text-red-500' }}" style="{{ $hasFavorited ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
                    <span class="text-sm font-bold {{ $hasFavorited ? 'text-red-500' : 'text-text-secondary' }}">{{ number_format($post->favorites_count) }} Favorites</span>
                </button>
            </form>
            @else
            <button aria-label="Login to Favorite" onclick="window.location.href='{{ route('login') }}'" class="flex items-center gap-2 focus:outline-none rounded-full p-2 group transition-all">
                <span class="material-symbols-outlined text-text-secondary group-hover:text-red-500 transition-colors">favorite</span>
                <span class="text-sm font-bold text-text-secondary">{{ number_format($post->favorites_count) }} Favorites</span>
            </button>
            @endauth
            <div class="w-px h-8 bg-border/60"></div>
            <a href="#comments" aria-label="Comment on post" class="flex items-center gap-2 focus:outline-none rounded-full p-2 group transition-all">
                <span class="material-symbols-outlined text-text-secondary group-hover:text-primary transition-colors">chat_bubble</span>
                <span class="text-sm font-bold text-text-secondary">{{ number_format($post->comments_count) }} Comments</span>
            </a>
            <div class="w-px h-8 bg-border/60"></div>
            @auth
            <form action="{{ route('posts.bookmark', $post->id) }}" method="POST" class="inline">
                @csrf
                @php $hasBookmarked = auth()->user()->bookmarks()->where('post_id', $post->id)->exists(); @endphp
                <button aria-label="Bookmark post" class="focus:outline-none rounded-full p-2 group transition-all hover:bg-surface-hover/50">
                    <span class="material-symbols-outlined transition-colors {{ $hasBookmarked ? 'text-primary' : 'text-text-secondary group-hover:text-primary' }}" style="{{ $hasBookmarked ? 'font-variation-settings: \'FILL\' 1;' : '' }}">bookmark</span>
                </button>
            </form>
            @else
            <button aria-label="Login to Bookmark" onclick="window.location.href='{{ route('login') }}'" class="focus:outline-none rounded-full p-2 group transition-all hover:bg-surface-hover/50">
                <span class="material-symbols-outlined text-text-secondary group-hover:text-primary transition-colors">bookmark</span>
            </button>
            @endauth
            <button aria-label="Share post" x-data @click="
                if (navigator.share) {
                    navigator.share({ title: '{{ addslashes($post->title) }}', url: '{{ route('posts.show', $post->slug) }}' }).catch(() => {});
                } else {
                    navigator.clipboard.writeText('{{ route('posts.show', $post->slug) }}');
                    showToast('Link copied to clipboard!');
                }
            " class="focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-full p-2 group hover:bg-surface-hover/50 transition-all">
                <span class="material-symbols-outlined text-text-secondary group-hover:text-primary transition-colors">ios_share</span>
            </button>
        </div>
    </div>

    <div id="comments" class="bg-surface rounded-3xl border border-border/40 shadow-xl shadow-surface-secondary/20 p-8 sm:p-12 mb-24 relative">
        <h2 class="text-2xl font-extrabold text-text-primary mb-8 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">forum</span>
            Comments ({{ $post->comments_count }})
        </h2>
        
        @auth
            <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-10">
                @csrf
                <div class="flex gap-4">
                    <x-ui.avatar :src="auth()->user()->avatar_url ?? asset('images/avatars/blank.png')" alt="Your Avatar" size="md" class="shrink-0" />
                    <div class="flex-1">
                        <textarea name="content" rows="3" class="w-full bg-surface-hover border border-border/60 text-text-primary rounded-2xl p-4 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none" placeholder="Add to the discussion..." required></textarea>
                        <div class="mt-3 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl shadow-md hover:bg-primary/90 transition-all active:scale-95">Post Comment</button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="bg-surface-hover border border-border/50 rounded-2xl p-6 text-center mb-10">
                <p class="text-text-secondary font-medium mb-3">Sign in to join the discussion.</p>
                <a href="{{ route('login') }}" class="inline-block px-6 py-2 border border-primary text-primary font-bold rounded-xl hover:bg-primary/5 transition-all">Sign In</a>
            </div>
        @endauth

        <div class="space-y-8">
            @foreach($post->comments as $comment)
                <div class="flex gap-4 group">
                    <x-ui.avatar :src="$comment->user?->avatar_url ?? asset('images/avatars/blank.png')" :alt="$comment->user_name" size="md" class="shrink-0" />
                    <div class="flex-1">
                        <div class="bg-surface-hover border border-border/40 rounded-2xl rounded-tl-none p-5 relative">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-text-primary">{{ $comment->user_name }}</span>
                                    <span class="text-xs text-text-tertiary">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                
                                @auth
                                    @if(auth()->id() === $comment->user_id || auth()->user()->hasAbility('posts.manage_all'))
                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete this comment?')" class="text-text-tertiary hover:text-red-500 transition-colors" title="Delete Comment">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                            <p class="text-text-secondary text-sm leading-relaxed">{{ $comment->content }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    </div>
</x-layout.app>