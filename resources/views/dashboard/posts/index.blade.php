<x-layout.app title="My Posts" :has-sidebar="true" :has-right-sidebar="false">
    <div class="px-4 py-8 lg:px-8 max-w-6xl mx-auto">
        
        <!-- Flash Message -->
        @if (session()->has('status'))
            <div class="mb-8 p-4 rounded-2xl bg-green-50/80 backdrop-blur-md border border-green-200/50 text-green-800 flex items-center gap-3 text-sm font-medium shadow-sm animate-fade-in-up">
                <span class="material-symbols-outlined text-[20px] text-green-600">check_circle</span>
                {{ session()->get('status') }}
            </div>
        @endif

        <!-- 1. Dashboard Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <x-ui.badge variant="primary" class="mb-4 bg-primary/10 text-primary border-primary/20">
                    <span class="material-symbols-outlined text-[14px] mr-1">auto_awesome</span> Content Studio
                </x-ui.badge>
                <h1 class="text-4xl lg:text-5xl font-extrabold text-text-primary tracking-tight mb-3">
                    My Posts
                </h1>
                <p class="text-text-secondary text-base lg:text-lg max-w-xl">
                    Create, manage and optimize your AI-powered content.
                </p>
            </div>
            
            <a href="{{ route('dashboard.posts.create') }}" class="group relative inline-flex items-center justify-center px-6 py-3.5 text-base font-bold text-white transition-all duration-300 bg-gradient-to-r from-primary to-accent rounded-2xl hover:shadow-[0_8px_25px_-8px_rgba(99,102,241,0.5)] hover:-translate-y-1 active:scale-95 shrink-0 overflow-hidden">
                <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full -translate-x-full transition-transform duration-700 ease-out skew-x-12"></div>
                <span class="material-symbols-outlined text-[20px] mr-2">add</span>
                Create New Post
            </a>
        </div>

        <!-- 2. Analytics Bento Section -->
        @php
            $totalPosts = 0;
            $publishedPosts = 0;
            $draftPosts = 0;
            
            foreach($status_options as $option) {
                $totalPosts += $option['count'];
                if(strtolower($option['name']) === 'published') $publishedPosts = $option['count'];
                if(strtolower($option['name']) === 'draft') $draftPosts = $option['count'];
            }
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- Total Posts -->
            <div class="group bg-surface rounded-2xl border border-border/50 p-6 shadow-sm hover:shadow-xl hover:shadow-primary/5 transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 duration-500">
                    <span class="material-symbols-outlined text-8xl text-text-primary">article</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-surface-secondary flex items-center justify-center mb-6 text-text-secondary group-hover:text-primary group-hover:bg-primary/10 transition-colors">
                    <span class="material-symbols-outlined text-[24px]">dataset</span>
                </div>
                <h3 class="text-4xl font-extrabold text-text-primary tracking-tight mb-2">{{ $totalPosts }}</h3>
                <p class="text-sm font-semibold text-text-primary mb-1">Total Posts</p>
                <p class="text-xs text-text-tertiary">All your created content</p>
            </div>

            <!-- Published Posts -->
            <div class="group bg-surface rounded-2xl border border-border/50 p-6 shadow-sm hover:shadow-xl hover:shadow-green-500/5 transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 duration-500">
                    <span class="material-symbols-outlined text-8xl text-green-500">public</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-surface-secondary flex items-center justify-center mb-6 text-text-secondary group-hover:text-green-500 group-hover:bg-green-50 transition-colors">
                    <span class="material-symbols-outlined text-[24px]">public</span>
                </div>
                <h3 class="text-4xl font-extrabold text-text-primary tracking-tight mb-2">{{ $publishedPosts }}</h3>
                <p class="text-sm font-semibold text-text-primary mb-1">Published</p>
                <p class="text-xs text-text-tertiary">Live on your site</p>
            </div>

            <!-- Draft Posts -->
            <div class="group bg-surface rounded-2xl border border-border/50 p-6 shadow-sm hover:shadow-xl hover:shadow-amber-500/5 transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 duration-500">
                    <span class="material-symbols-outlined text-8xl text-amber-500">edit_document</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-surface-secondary flex items-center justify-center mb-6 text-text-secondary group-hover:text-amber-500 group-hover:bg-amber-50 transition-colors">
                    <span class="material-symbols-outlined text-[24px]">edit_document</span>
                </div>
                <h3 class="text-4xl font-extrabold text-text-primary tracking-tight mb-2">{{ $draftPosts }}</h3>
                <p class="text-sm font-semibold text-text-primary mb-1">Drafts</p>
                <p class="text-xs text-text-tertiary">Works in progress</p>
            </div>
        </div>

        <!-- 3. Modern Filter System -->
        <div class="glass sticky top-[72px] lg:top-20 z-30 flex flex-col sm:flex-row sm:items-center justify-between p-2 rounded-2xl shadow-sm border border-border/50 mb-8 gap-4">
            <div class="flex gap-2 overflow-x-auto no-scrollbar p-1">
                @foreach ($status_options as $option)
                    @php
                        $isActive = $status == strtolower($option['name']);
                        $icon = match(strtolower($option['name'])) {
                            'published' => 'check_circle',
                            'draft' => 'edit_document',
                            'archived' => 'archive',
                            default => 'article'
                        };
                    @endphp
                    <a href="{{ route('dashboard.posts.index', ['status' => strtolower($option['name'])]) }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-300 flex items-center gap-2 {{ $isActive ? 'bg-gradient-to-r from-primary to-accent text-white shadow-md shadow-primary/20' : 'text-text-secondary hover:text-text-primary hover:bg-surface-hover' }}">
                        <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
                        {{ $option['name'] }}
                        <span class="{{ $isActive ? 'bg-white/20 text-white' : 'bg-surface-secondary text-text-tertiary group-hover:bg-border/50' }} px-2 py-0.5 rounded-full text-[10px] font-black tracking-wider transition-colors">
                            {{ $option['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>
            
            <div class="px-4 text-sm font-semibold text-text-tertiary hidden md:block">
                Showing {{ $posts->count() }} of {{ $posts->total() }} results
            </div>
        </div>

        <!-- 4. Posts Display -->
        <div class="space-y-6">
            @forelse ($posts as $post)
                <div class="group bg-surface hover:bg-surface/80 rounded-2xl border border-border/50 p-6 shadow-sm hover:shadow-premium transition-all duration-300 hover:-translate-y-1 relative">
                    
                    <!-- Post Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <x-ui.avatar :src="$post->user?->avatar_url ?? asset('images/avatars/blank.png')" :alt="$post->user?->name ?? 'User'" size="sm" />
                            <div>
                                <p class="text-sm font-bold text-text-primary leading-none mb-1">{{ $post->user?->name ?? 'Unknown Author' }}</p>
                                <p class="text-xs text-text-tertiary flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                    {{ $post->created_at->format('M j, Y') }}
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            @if ($post->trashed())
                                <x-ui.badge variant="danger" class="border border-red-200 shadow-sm flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">delete</span>
                                    Deleted
                                </x-ui.badge>
                            @else
                                @php
                                    $statusName = strtolower($post->status->getLabel());
                                    if ($statusName === 'draft') {
                                        $badgeColor = 'warning';
                                        $badgeIcon = 'edit';
                                    } elseif ($statusName === 'published') {
                                        $badgeColor = 'success';
                                        $badgeIcon = 'check';
                                    } elseif ($statusName === 'archived') {
                                        $badgeColor = 'secondary';
                                        $badgeIcon = 'archive';
                                    } else {
                                        $colorMap = ['gray' => 'secondary', 'green' => 'success', 'blue' => 'primary', 'yellow' => 'warning', 'red' => 'danger'];
                                        $badgeColor = $colorMap[$post->status->getColor()] ?? 'secondary';
                                        $badgeIcon = 'circle';
                                    }
                                @endphp
                                <x-ui.badge variant="{{ $badgeColor }}" class="shadow-sm flex items-center gap-1 px-3 py-1">
                                    <span class="material-symbols-outlined text-[14px]">{{ $badgeIcon }}</span>
                                    {{ $post->status->getLabel() }}
                                </x-ui.badge>
                            @endif
                        </div>
                    </div>

                    <!-- Post Content -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-6">
                        @if($post->cover_image)
                        <a href="{{ route('posts.show', $post->slug ?? '') }}" target="_blank" class="w-full sm:w-48 h-32 rounded-xl overflow-hidden shrink-0 block group/img">
                            <img src="{{ $post->thumbnail_url ?? Storage::url($post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover/img:scale-105">
                        </a>
                        @endif
                        <div class="flex-1 flex flex-col justify-center">
                            <a href="{{ route('posts.show', $post->slug ?? '') }}" target="_blank">
                                <h3 class="text-xl font-extrabold text-text-primary leading-tight group-hover:text-primary transition-colors mb-2 line-clamp-1">
                                    {{ $post->title }}
                                </h3>
                            </a>
                            <p class="text-sm text-text-secondary leading-relaxed line-clamp-2 max-w-3xl mb-3">
                                {{ Str::limit(strip_tags($post->content ?? ''), 150) }}
                            </p>
                            
                            @if($post->tags && count($post->tags) > 0)
                            <div class="flex flex-wrap gap-2 mt-auto">
                                @foreach($post->tags as $tag)
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-md bg-surface-secondary text-text-tertiary">#{{ $tag->name ?? $tag }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Post Footer & Actions -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-border/50">
                        <div class="flex items-center gap-4 text-xs font-semibold text-text-tertiary">
                            <span class="flex items-center gap-1.5 bg-surface-secondary px-3 py-1.5 rounded-lg">
                                <span class="material-symbols-outlined text-[16px]">visibility</span>
                                {{ number_format($post->views) }} Views
                            </span>
                            <span class="flex items-center gap-1.5 bg-surface-secondary px-3 py-1.5 rounded-lg" title="Favorites">
                                <span class="material-symbols-outlined text-[16px]">favorite</span> 
                                {{ $post->favorites_count ?? 0 }} Favorites
                            </span>
                            <span class="flex items-center gap-1.5 bg-surface-secondary px-3 py-1.5 rounded-lg" title="Comments">
                                <span class="material-symbols-outlined text-[16px]">chat_bubble</span> 
                                {{ $post->comments_count ?? 0 }} Comments
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-2 w-full sm:w-auto flex-wrap sm:flex-nowrap">
                            @if($post->trashed())
                                <form action="{{ route('dashboard.posts.restore', $post->id) }}" method="post" class="w-full sm:w-auto" x-data="{ loading: false }" @submit="loading = true">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="flex items-center justify-center gap-2 px-4 py-2 w-full sm:w-auto text-sm font-bold text-green-600 bg-green-50 hover:bg-green-100 rounded-xl transition-colors active:scale-95" onclick="return confirm('Restore this post?');">
                                        <span class="material-symbols-outlined text-[18px]">refresh</span> Restore
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('posts.show', $post->slug ?? '') }}" target="_blank" class="flex items-center justify-center gap-2 px-4 py-2 w-full sm:w-auto flex-1 sm:flex-none text-sm font-bold text-text-secondary bg-surface-secondary hover:bg-surface-hover hover:text-text-primary rounded-xl transition-colors active:scale-95">
                                    <span class="material-symbols-outlined text-[18px]">open_in_new</span> View
                                </a>
                                @can('update', $post)
                                <a href="{{ route('dashboard.posts.edit', $post->id) }}" class="flex items-center justify-center gap-2 px-4 py-2 w-full sm:w-auto flex-1 sm:flex-none text-sm font-bold text-primary bg-primary/10 hover:bg-primary/20 rounded-xl transition-colors active:scale-95">
                                    <span class="material-symbols-outlined text-[18px]">edit</span> Edit Post
                                </a>
                                @endcan
                            @endif
                            
                            @can('delete', $post)
                            <form action="{{ route('dashboard.posts.' . ($post->trashed() ? 'force-delete' : 'destroy'), $post->id) }}" method="post" class="w-full sm:w-auto" x-data="{ loading: false }" @submit="loading = true">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center justify-center gap-2 px-4 py-2 w-full sm:w-auto text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors active:scale-95" onclick="return confirm('Are you sure you want to delete this post?');">
                                    <span class="material-symbols-outlined text-[18px]">delete</span> Delete
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
            @empty
                <!-- 5. Empty State -->
                <div class="glass rounded-3xl p-12 md:p-20 text-center border border-border/50 shadow-sm flex flex-col items-center justify-center animate-fade-in-up">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary/10 to-accent/10 rounded-full flex items-center justify-center mb-6 shadow-inner">
                        <span class="material-symbols-outlined text-5xl text-primary" style="font-variation-settings: 'FILL' 1;">post_add</span>
                    </div>
                    <h2 class="text-3xl font-extrabold text-text-primary mb-3">No content yet</h2>
                    <p class="text-text-secondary text-lg max-w-md mx-auto mb-8">
                        Start creating your first AI-powered article and share your knowledge with the world.
                    </p>
                    <a href="{{ route('dashboard.posts.create') }}" class="group inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-300 bg-gradient-to-r from-primary to-accent rounded-2xl hover:shadow-[0_8px_25px_-8px_rgba(99,102,241,0.5)] hover:-translate-y-1 active:scale-95">
                        <span class="material-symbols-outlined text-[20px] mr-2">add</span>
                        Create your first post
                    </a>
                </div>
            @endforelse
        </div>

        <!-- 6. Pagination -->
        <div class="mt-12">
            {{ $posts->withQueryString()->links('pagination.custom-tailwind') }}
        </div>
        
    </div>
</x-layout.app>
