<x-layout.app :title="$user->name" :has-sidebar="true" :has-right-sidebar="false">
    <div class="max-w-4xl mx-auto pb-24">
        
        <!-- Profile Header -->
        <div class="bg-surface rounded-3xl border border-border/40 shadow-sm p-8 sm:p-12 mb-12 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-primary/5 to-transparent pointer-events-none"></div>
            
            <x-ui.avatar :src="$user->avatar_url ?? asset('images/avatars/blank.png')" :alt="$user->name" size="xl" class="mx-auto mb-6 ring-4 ring-surface shadow-lg relative z-10" />
            
            <h1 class="text-3xl font-extrabold text-text-primary mb-2 flex items-center justify-center gap-2 relative z-10">
                {{ $user->name }}
                @if($user->hasAbility('verified') ?? false)
                    <span class="material-symbols-outlined text-primary text-[22px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                @endif
            </h1>
            
            <p class="text-text-tertiary font-medium mb-6 relative z-10">{{ '@' . $user->username }}</p>
            
            @if($user->bio)
                <p class="text-text-secondary max-w-2xl mx-auto leading-relaxed relative z-10">{{ $user->bio }}</p>
            @endif
            
            @if(auth()->check() && auth()->id() !== $user->id)
                <div class="mt-6 flex justify-center relative z-10">
                    @php
                        $isFollowing = auth()->user()->followings()->where('user_id', $user->id)->exists();
                    @endphp
                    @if($isFollowing)
                        <form action="{{ route('users.unfollow', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-bold text-text-secondary bg-surface border border-border px-6 py-2 rounded-full hover:bg-surface-secondary transition-colors shadow-sm">
                                Following
                            </button>
                        </form>
                    @else
                        <form action="{{ route('users.follow', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="font-bold text-white bg-primary px-6 py-2 rounded-full hover:bg-primary-hover transition-colors shadow-md shadow-primary/20">
                                Follow
                            </button>
                        </form>
                    @endif
                </div>
            @endif
            
            <div class="flex justify-center items-center gap-6 mt-8 pt-8 border-t border-border/50 relative z-10">
                <div class="text-center">
                    <div class="text-2xl font-black text-text-primary">{{ $posts->total() }}</div>
                    <div class="text-xs text-text-tertiary font-bold uppercase tracking-wider">Posts</div>
                </div>
            </div>
        </div>

        <!-- User Posts -->
        <h2 class="text-2xl font-extrabold text-text-primary mb-8 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">article</span>
            Published Articles
        </h2>

        <div class="space-y-6">
            @forelse ($posts as $post)
                <a href="{{ route('posts.show', $post->slug) }}" class="block group bg-surface hover:bg-surface-hover rounded-2xl border border-border/50 p-6 shadow-sm hover:shadow-premium transition-all duration-300 hover:-translate-y-1">
                    <div class="flex flex-col sm:flex-row gap-6">
                        @if($post->cover_image)
                            <div class="w-full sm:w-48 h-32 rounded-xl overflow-hidden shrink-0">
                                <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            </div>
                        @endif
                        <div class="flex-1 flex flex-col justify-center">
                            <h3 class="text-xl font-extrabold text-text-primary leading-tight group-hover:text-primary transition-colors mb-2 line-clamp-2">
                                {{ $post->title }}
                            </h3>
                            <p class="text-sm text-text-secondary leading-relaxed line-clamp-2 mb-4">
                                {{ Str::limit(strip_tags($post->content), 120) }}
                            </p>
                            <div class="flex items-center gap-4 text-xs font-semibold text-text-tertiary">
                                <span>{{ $post->created_at->format('M j, Y') }}</span>
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">visibility</span> {{ number_format($post->views) }}</span>
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">favorite</span> {{ number_format($post->favorites_count ?? 0) }}</span>
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">chat_bubble</span> {{ number_format($post->comments_count ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-surface border border-border/50 rounded-2xl p-12 text-center">
                    <span class="material-symbols-outlined text-4xl text-text-tertiary mb-3">post_add</span>
                    <h3 class="text-lg font-bold text-text-primary mb-1">No articles yet</h3>
                    <p class="text-text-secondary">This author hasn't published any articles.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>

    </div>
</x-layout.app>
