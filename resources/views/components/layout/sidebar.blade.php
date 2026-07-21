<aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed inset-y-0 left-0 lg:sticky lg:top-0 h-screen transform transition-transform duration-300 w-64 flex flex-col pt-6 pb-4 px-4 shrink-0 z-50 lg:z-40 bg-surface backdrop-blur-md border-r border-border/50">
    <!-- Logo -->
    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-8 px-2 group shrink-0">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-accent flex items-center justify-center shadow-sm group-hover:shadow transition-shadow">
            <span class="material-symbols-outlined text-white text-[18px]" style="font-variation-settings: 'FILL' 1;">edit_document</span>
        </div>
        <span class="font-bold text-lg tracking-tight text-text-primary">{{ config('app.name', 'Write AI') }}</span>
    </a>

    <!-- Navigation -->
    <nav class="flex flex-col gap-1 flex-1 overflow-y-auto no-scrollbar mb-4">
        <x-layout.sidebar-item href="{{ route('home') }}" icon="home" :active="request()->routeIs('home')">
            Home
        </x-layout.sidebar-item>

        <x-layout.sidebar-item href="{{ route('dashboard.posts.index') }}" icon="article" :active="request()->routeIs('dashboard.posts.*')">
            Posts
        </x-layout.sidebar-item>

        <x-layout.sidebar-item href="{{ route('dashboard.notifications.index') }}" icon="notifications" :active="request()->routeIs('dashboard.notifications.*')" :badge="auth()->check() && auth()->user()->unreadNotifications->count() > 0 ? auth()->user()->unreadNotifications->count() : null">
            Notifications
        </x-layout.sidebar-item>

        <x-layout.sidebar-item href="{{ route('roles.index') }}" icon="shield_person" :active="request()->routeIs('roles.*')">
            Role Management
        </x-layout.sidebar-item>

        <x-layout.sidebar-item href="{{ route('users.index') }}" icon="group" :active="request()->routeIs('users.*')">
            User Administration
        </x-layout.sidebar-item>
    </nav>

    <!-- User Profile Snippet (Bottom) -->
    @auth
    <div class="mt-auto shrink-0 relative pt-4 border-t border-border/50" x-data="{ open: false }">
        <button @click="open = !open" class="flex items-center gap-3 p-2 w-full rounded-lg hover:bg-surface-hover transition-colors text-left focus:outline-none">
            <x-ui.avatar :src="auth()->user()->avatarUrl ?? asset('images/avatars/blank.png')" :alt="auth()->user()->name" size="sm" />
            <div class="flex-1 overflow-hidden">
                <p class="text-sm font-semibold text-text-primary truncate leading-tight">{{ auth()->user()->name }}</p>
                <p class="text-xs text-text-tertiary truncate leading-tight">{{ '@' . auth()->user()->username }}</p>
            </div>
            <span class="material-symbols-outlined text-text-tertiary text-[18px]">unfold_more</span>
        </button>

        <!-- Dropdown menu -->
        <div x-show="open" @click.away="open = false" x-transition.opacity.duration.200ms class="absolute bottom-full mb-2 left-0 w-full bg-surface rounded-xl border border-border shadow-md py-1 z-50" x-cloak>
            <a href="{{ route('users.show', auth()->user()->username) }}" class="flex items-center gap-2 px-3 py-2 text-sm text-text-secondary hover:text-primary hover:bg-surface-hover transition-colors">
                <span class="material-symbols-outlined text-[18px]">person</span>
                View Profile
            </a>
            <a href="{{ route('dashboard.posts.create') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-text-secondary hover:text-primary hover:bg-surface-hover transition-colors">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                New Post
            </a>
            <hr class="border-border/50 my-1">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-3 py-2 text-sm text-red-500 hover:text-red-600 hover:bg-red-50 transition-colors w-full text-left">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                    Log Out
                </button>
            </form>
        </div>
    </div>
    @endauth

    @guest
    <div class="mt-auto shrink-0">
        <x-ui.button variant="primary" className="w-full justify-center" href="/auth/login">
            Log In
        </x-ui.button>
    </div>
    @endguest
</aside>
