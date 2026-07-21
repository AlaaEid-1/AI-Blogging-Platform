@props(['post'])

<x-ui.card className="mb-6 flex flex-col group/card" glass="false">
    <!-- Header: User info & Actions -->
    <div class="p-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('users.show', $post->user?->username ?? 'unknown') }}" class="shrink-0">
                <x-ui.avatar :src="$post->user?->avatar_url ?? asset('images/avatars/blank.png')" :alt="$post->user?->name ?? 'Unknown User'" size="md" />
            </a>
            <div>
                <div class="flex items-center gap-1">
                    <a href="{{ route('users.show', $post->user?->username ?? 'unknown') }}" class="font-semibold text-text-primary text-sm hover:text-primary transition-colors">{{ $post->user?->name ?? 'Unknown User' }}</a>
                    @if($post->user?->hasAbility('verified') ?? false)
                        <span class="material-symbols-outlined text-primary text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                    @endif
                </div>
                <div class="text-xs text-text-tertiary flex items-center gap-1">
                    <span>{{ $post->publish_time ? $post->publish_time->diffForHumans() : 'Just now' }}</span>
                    <span>&bull;</span>
                    <span>{{ $post->read_time ?? 1 }} min read</span>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            <button aria-label="More options" class="text-text-tertiary hover:text-text-primary transition-colors p-1 rounded-full hover:bg-surface-hover focus:outline-none focus:ring-2 focus:ring-primary">
                <span class="material-symbols-outlined">more_horiz</span>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="px-5 pb-4">
        <a href="{{ route('posts.show', $post->slug) }}" class="block group">
            <h2 class="text-xl font-bold text-text-primary mb-2 group-hover:text-primary transition-colors line-clamp-2">
                {{ $post->title }}
            </h2>
            <p class="text-text-secondary text-sm leading-relaxed mb-4 line-clamp-3">
                {{ $post->excerpt ?? Str::limit(strip_tags($post->content ?? ''), 150) }}
            </p>
        </a>

        <!-- Tags -->
        @if($post->tags && $post->tags->count() > 0)
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($post->tags->take(3) as $tag)
                    <x-ui.badge variant="secondary">#{{ $tag->name }}</x-ui.badge>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Cover Image -->
    @if($post->cover_image)
        <a href="{{ route('posts.show', $post->slug) }}" class="block w-full max-h-96 overflow-hidden bg-surface-hover">
            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
        </a>
    @endif

    <!-- Footer: Engagement Actions -->
    <div class="px-5 py-3 border-t border-border flex items-center justify-between">
        <div class="flex items-center gap-1 sm:gap-4">
            @php
                static $userFavorites = null;
                static $userBookmarks = null;
                if (auth()->check()) {
                    if ($userFavorites === null) {
                        $userFavorites = auth()->user()->favorites()->pluck('post_id')->toArray();
                    }
                    if ($userBookmarks === null) {
                        $userBookmarks = auth()->user()->bookmarks()->pluck('post_id')->toArray();
                    }
                }
                $hasFavorited = auth()->check() ? in_array($post->id, $userFavorites) : false;
                $hasBookmarked = auth()->check() ? in_array($post->id, $userBookmarks) : false;
            @endphp
            @auth
                <form action="{{ route('favorites.toggle', $post->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" aria-label="Favorite post" class="flex items-center gap-1 focus:outline-none rounded-full p-2 group transition-all">
                        <span class="material-symbols-outlined transition-colors {{ $hasFavorited ? 'text-red-500' : 'text-text-secondary group-hover:text-red-500' }}" style="{{ $hasFavorited ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
                        <span class="text-sm font-bold {{ $hasFavorited ? 'text-red-500' : 'text-text-secondary group-hover:text-red-500' }}">{{ $post->favorites_count ?? 0 }}</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" aria-label="Login to Favorite" class="flex items-center gap-1 focus:outline-none rounded-full p-2 group transition-all">
                    <span class="material-symbols-outlined text-text-secondary group-hover:text-red-500 transition-colors">favorite</span>
                    <span class="text-sm font-bold text-text-secondary group-hover:text-red-500">{{ $post->favorites_count ?? 0 }}</span>
                </a>
            @endauth
            
            <a href="{{ route('posts.show', $post->slug) }}#comments" aria-label="Comment on post" class="flex items-center gap-1 focus:outline-none rounded-full p-2 group transition-all">
                <span class="material-symbols-outlined text-text-secondary group-hover:text-primary transition-colors">chat_bubble</span>
                <span class="text-sm font-bold text-text-secondary group-hover:text-primary">{{ $post->comments_count ?? 0 }}</span>
            </a>

            <div x-data>
                <x-ui.button aria-label="Share post" @click="
                    if (navigator.share) {
                        navigator.share({ title: '{{ addslashes($post->title) }}', url: '{{ route('posts.show', $post->slug) }}' }).catch(() => {});
                    } else {
                        navigator.clipboard.writeText('{{ route('posts.show', $post->slug) }}');
                        showToast('Link copied to clipboard!');
                    }
                " variant="ghost" size="sm" icon="ios_share" className="hover:text-primary hover:bg-primary/5" />
            </div>
        </div>
        
        <div class="flex items-center gap-1 sm:gap-4">
            <div class="flex items-center text-text-tertiary text-sm gap-1 mr-2">
                <span class="material-symbols-outlined text-[18px]">visibility</span>
                <span>{{ number_format($post->views ?? 0) }}</span>
            </div>
            @auth
                <form action="{{ route('posts.bookmark', $post->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" aria-label="Bookmark post" class="inline-flex items-center justify-center gap-2 font-medium transition-all duration-200 rounded-lg active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-transparent hover:bg-primary/5 text-sm px-3 py-1.5 group {{ $hasBookmarked ? 'text-primary' : 'text-text-secondary hover:text-primary' }}">
                        <span class="material-symbols-outlined text-[1.2em]" style="{{ $hasBookmarked ? 'font-variation-settings: \'FILL\' 1;' : '' }}">bookmark</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" aria-label="Login to Bookmark" class="inline-flex items-center justify-center gap-2 font-medium transition-all duration-200 rounded-lg active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-transparent hover:bg-primary/5 text-text-secondary hover:text-primary text-sm px-3 py-1.5 group">
                    <span class="material-symbols-outlined text-[1.2em]">bookmark</span>
                </a>
            @endauth
        </div>
    </div>
</x-ui.card>
