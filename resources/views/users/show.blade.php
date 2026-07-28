<x-layout.app
    :title="$user->name . ' (@' . $user->username . ') — ' . config('app.name')"
    :has-sidebar="true"
    :has-right-sidebar="false"
>
    <div class="max-w-4xl mx-auto pb-24">

        {{-- ============================================================ --}}
        {{-- PROFILE HERO CARD --}}
        {{-- ============================================================ --}}
        <div class="relative mb-10 rounded-3xl overflow-hidden bg-surface border border-border/40 shadow-soft">

            {{-- Gradient Banner --}}
            <div class="h-36 sm:h-48 bg-gradient-to-br from-primary/80 via-accent/70 to-primary-dark/90 relative overflow-hidden">
                {{-- Decorative orbs --}}
                <div class="absolute -top-10 -left-10 w-48 h-48 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute -bottom-8 right-12 w-36 h-36 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute top-4 right-4 opacity-20">
                    <span class="material-symbols-outlined text-white text-[80px]" style="font-variation-settings: 'FILL' 1;">auto_stories</span>
                </div>
            </div>

            {{-- Profile Content Area --}}
            <div class="px-6 sm:px-10 pb-8">
                {{-- Avatar (overlapping banner) --}}
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 -mt-14 sm:-mt-16 mb-6">
                    <div class="relative inline-block">
                        <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl overflow-hidden border-4 border-surface shadow-premium bg-surface ring-4 ring-primary/10">
                            <img
                                src="{{ $user->avatar_url }}"
                                alt="{{ $user->name }}"
                                class="w-full h-full object-cover"
                            >
                        </div>
                        @if($user->hasAbility('verified') ?? false)
                            <span
                                class="absolute -bottom-2 -right-2 w-7 h-7 rounded-full bg-primary flex items-center justify-center shadow-md ring-2 ring-surface"
                                title="Verified author"
                            >
                                <span class="material-symbols-outlined text-white text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                            </span>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-3 flex-wrap mt-2 sm:mt-0">
                        @auth
                            @if(auth()->id() === $user->id)
                                {{-- Owner: Edit Profile --}}
                                <a
                                    href="{{ route('profile.edit') }}"
                                    id="edit-profile-btn"
                                    class="inline-flex items-center gap-2 px-5 py-2 rounded-xl border border-border bg-surface text-text-secondary text-sm font-semibold hover:bg-surface-hover hover:text-primary transition-all duration-200 shadow-sm active:scale-95"
                                >
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                    Edit Profile
                                </a>
                            @elseif(auth()->user()->hasAbility('users.manage'))
                                {{-- Admin: Edit User in Admin Panel --}}
                                <a
                                    href="{{ route('users.edit', $user) }}"
                                    id="admin-edit-profile-btn"
                                    class="inline-flex items-center gap-2 px-5 py-2 rounded-xl border border-border bg-surface text-text-secondary text-sm font-semibold hover:bg-surface-hover hover:text-primary transition-all duration-200 shadow-sm active:scale-95"
                                >
                                    <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span>
                                    Admin Edit
                                </a>
                            @else
                                {{-- Visitor: Follow / Unfollow --}}
                                @if($isFollowing)
                                    <form
                                        id="unfollow-form"
                                        action="{{ route('users.unfollow', $user->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 px-5 py-2 rounded-xl border border-border bg-surface text-text-secondary text-sm font-semibold hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-all duration-200 shadow-sm active:scale-95"
                                        >
                                            <span class="material-symbols-outlined text-[18px]">person_remove</span>
                                            Following
                                        </button>
                                    </form>
                                @else
                                    <form
                                        id="follow-form"
                                        action="{{ route('users.follow', $user->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-all duration-200 shadow-md shadow-primary/20 active:scale-95"
                                        >
                                            <span class="material-symbols-outlined text-[18px]">person_add</span>
                                            Follow
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @else
                            {{-- Guest: Prompt to log in to follow --}}
                            <a
                                href="{{ route('login') }}"
                                id="login-to-follow-btn"
                                class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-all duration-200 shadow-md shadow-primary/20 active:scale-95"
                            >
                                <span class="material-symbols-outlined text-[18px]">person_add</span>
                                Follow
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Name & Username --}}
                <div class="mb-3">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-text-primary leading-tight tracking-tight">
                        {{ $user->name }}
                    </h1>
                    <p class="text-text-tertiary font-medium mt-0.5 text-sm">
                        {{ '@' . $user->username }}
                    </p>
                </div>

                {{-- Bio --}}
                @if($user->bio)
                    <p class="text-text-secondary leading-relaxed max-w-2xl mb-4 text-sm sm:text-base">
                        {{ $user->bio }}
                    </p>
                @endif

                {{-- Meta: Joined date --}}
                <div class="flex items-center gap-4 text-xs text-text-tertiary mb-6 flex-wrap">
                    <span class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                        Joined {{ $user->created_at->format('F Y') }}
                    </span>
                    @if($user->country_code)
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">public</span>
                            {{ strtoupper($user->country_code) }}
                        </span>
                    @endif
                </div>

                {{-- Stats Row --}}
                <div class="grid grid-cols-3 gap-3 sm:gap-6 pt-6 border-t border-border/50">

                    {{-- Posts --}}
                    <div class="text-center group">
                        <div class="text-2xl sm:text-3xl font-black text-text-primary group-hover:text-primary transition-colors">
                            {{ number_format($user->posts_count ?? 0) }}
                        </div>
                        <div class="text-xs sm:text-sm text-text-tertiary font-semibold uppercase tracking-wider mt-0.5">
                            {{ Str::plural('Article', $user->posts_count ?? 0) }}
                        </div>
                    </div>

                    {{-- Followers --}}
                    <div class="text-center group border-x border-border/40">
                        <div class="text-2xl sm:text-3xl font-black text-text-primary group-hover:text-primary transition-colors">
                            {{ number_format($user->followers_count ?? 0) }}
                        </div>
                        <div class="text-xs sm:text-sm text-text-tertiary font-semibold uppercase tracking-wider mt-0.5">
                            {{ Str::plural('Follower', $user->followers_count ?? 0) }}
                        </div>
                    </div>

                    {{-- Following --}}
                    <div class="text-center group">
                        <div class="text-2xl sm:text-3xl font-black text-text-primary group-hover:text-primary transition-colors">
                            {{ number_format($user->followings_count ?? 0) }}
                        </div>
                        <div class="text-xs sm:text-sm text-text-tertiary font-semibold uppercase tracking-wider mt-0.5">
                            Following
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- PUBLISHED ARTICLES --}}
        {{-- ============================================================ --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-7 rounded-full bg-gradient-to-b from-primary to-accent"></div>
            <h2 class="text-xl font-extrabold text-text-primary tracking-tight">
                Published Articles
            </h2>
            @if($posts->total() > 0)
                <span class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary/10 text-primary">
                    {{ number_format($posts->total()) }}
                </span>
            @endif
        </div>

        <div class="space-y-4">
            @forelse ($posts as $post)
                <a
                    href="{{ route('posts.show', $post->slug) }}"
                    id="post-{{ $post->id }}"
                    class="block group bg-surface rounded-2xl border border-border/50 hover:border-primary/20 shadow-soft hover:shadow-premium transition-all duration-300 hover:-translate-y-0.5 overflow-hidden"
                >
                    <div class="flex flex-col sm:flex-row gap-0">

                        {{-- Cover Image --}}
                        @if($post->cover_image)
                            <div class="sm:w-52 w-full h-44 sm:h-auto shrink-0 overflow-hidden bg-surface-hover">
                                <img
                                    src="{{ $post->thumbnail_url }}"
                                    alt="{{ $post->title }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                    loading="lazy"
                                >
                            </div>
                        @endif

                        {{-- Content --}}
                        <div class="flex-1 p-5 sm:p-6 flex flex-col justify-between">
                            <div>
                                {{-- Category badge --}}
                                @if($post->category && $post->category->slug !== 'uncategorized')
                                    <span class="inline-block mb-2 px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wider uppercase bg-primary/8 text-primary border border-primary/15">
                                        {{ $post->category->name }}
                                    </span>
                                @endif

                                <h3 class="text-lg sm:text-xl font-extrabold text-text-primary leading-snug group-hover:text-primary transition-colors mb-2 line-clamp-2 tracking-tight">
                                    {{ $post->title }}
                                </h3>

                                <p class="text-sm text-text-secondary leading-relaxed line-clamp-2 mb-3">
                                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content ?? ''), 130) }}
                                </p>

                                {{-- Tags --}}
                                @if($post->tags && $post->tags->count() > 0)
                                    <div class="flex flex-wrap gap-1.5 mb-3">
                                        @foreach($post->tags->take(3) as $tag)
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[11px] font-semibold bg-surface-hover text-text-tertiary border border-border/60">
                                                #{{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Footer meta --}}
                            <div class="flex items-center justify-between gap-4 pt-3 border-t border-border/40">
                                <div class="flex items-center gap-3 text-xs font-semibold text-text-tertiary flex-wrap">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                                        {{ $post->publish_time?->format('M j, Y') ?? $post->created_at->format('M j, Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">timer</span>
                                        {{ $post->read_time ?? 1 }} min
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 text-xs font-semibold text-text-tertiary">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">visibility</span>
                                        {{ number_format($post->views) }}
                                    </span>
                                    <span class="flex items-center gap-1 hover:text-red-500 transition-colors">
                                        <span class="material-symbols-outlined text-[14px]">favorite</span>
                                        {{ number_format($post->favorites_count ?? 0) }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">chat_bubble</span>
                                        {{ number_format($post->comments_count ?? 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <x-ui.empty-state
                    icon="post_add"
                    title="No articles yet"
                    description="This author hasn't published any articles yet. Check back soon!"
                />
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @endif

    </div>
</x-layout.app>
