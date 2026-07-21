@props([
    'title' => config('app.name', 'Write AI'), 
    'hasSidebar' => true, 
    'hasRightSidebar' => false,
    'authLayout' => false
])

<!DOCTYPE html>
<html lang="en" class="antialiased bg-background">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" />

    <script>
        const USER_ID = "{{ auth()->id() }}";
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    {{ $headScripts ?? '' }}
</head>
<body class="font-sans text-text-primary overflow-hidden selection:bg-primary/20 selection:text-primary bg-gradient-to-br from-[#F8F9FE] to-[#F1F5F9] h-screen w-full"
      x-data="{ mobileSidebarOpen: false, showToast(msg, type='success') { $dispatch('notify', { message: msg, type: type }) } }"
      :class="mobileSidebarOpen ? 'overflow-hidden' : ''"
>
    <!-- Subtle Ambient Gradients -->
    <div class="fixed inset-0 z-[-1] pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-primary/5 blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-accent/5 blur-[100px]"></div>
    </div>

    <div class="h-screen flex w-full relative overflow-hidden">
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-background/80 backdrop-blur-sm lg:hidden" x-cloak></div>
        
        <!-- Left Sidebar (Desktop) -->
        @if($hasSidebar && !$authLayout)
            <x-layout.sidebar />
        @endif

        <!-- Main Content Column -->
        <main class="flex-1 w-full h-screen overflow-y-auto pb-20 lg:pb-0 {{ $authLayout ? 'flex flex-col items-center justify-center' : '' }}">
            <!-- Mobile Header -->
            @if($hasSidebar && !$authLayout)
            <header class="lg:hidden flex items-center justify-between p-4 sticky top-0 z-40 bg-surface/80 backdrop-blur-md border-b border-border/50">
                <div class="flex items-center gap-2">
                    <button @click="mobileSidebarOpen = true" aria-label="Menu" class="text-text-secondary focus:outline-none focus:ring-2 focus:ring-primary rounded p-1"><span class="material-symbols-outlined">menu</span></button>
                    <a href="{{ route('home') }}" class="font-bold text-xl tracking-tight text-text-primary flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-accent flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-[18px]" style="font-variation-settings: 'FILL' 1;">edit_document</span>
                        </div>
                        <span class="hidden sm:inline">{{ config('app.name', 'Write AI') }}</span>
                    </a>
                </div>
                <div class="flex items-center gap-3">
                    <button aria-label="Search" class="text-text-secondary focus:outline-none focus:ring-2 focus:ring-primary rounded p-1"><span class="material-symbols-outlined">search</span></button>
                    <button aria-label="Notifications" class="text-text-secondary relative focus:outline-none focus:ring-2 focus:ring-primary rounded p-1">
                        <span class="material-symbols-outlined">notifications</span>
                        @auth
                            @if(auth()->user()->unreadNotifications->count())
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border border-surface"></span>
                            @endif
                        @endauth
                    </button>
                </div>
            </header>
            @endif

            <div class="{{ $authLayout ? 'w-full' : 'w-full pt-6 px-4 sm:px-6 lg:px-8' }}">
                {{ $slot }}
            </div>
        </main>

        <!-- Right Sidebar (Desktop) -->
        @if($hasRightSidebar && !$authLayout)
            <x-layout.right-sidebar />
        @endif

        <!-- Mobile Bottom Nav -->
        @if($hasSidebar && !$authLayout)
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-surface border-t border-border/50 pb-safe z-40 flex items-center justify-around p-2">
            <a href="{{ route('home') }}" class="p-2 {{ request()->routeIs('home') ? 'text-primary' : 'text-text-tertiary' }}">
                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' {{ request()->routeIs('home') ? '1' : '0' }};">home</span>
            </a>
            @auth
            <a href="{{ route('dashboard.posts.index') }}" class="p-2 {{ request()->routeIs('dashboard.posts.*') ? 'text-primary' : 'text-text-tertiary' }}">
                <span class="material-symbols-outlined text-[28px]">article</span>
            </a>
            <a href="{{ route('dashboard.notifications.index') }}" class="p-2 {{ request()->routeIs('dashboard.notifications.*') ? 'text-primary' : 'text-text-tertiary' }}">
                <span class="material-symbols-outlined text-[28px]">notifications</span>
            </a>
            <a href="{{ route('dashboard.posts.create') }}" class="p-2">
                <x-ui.avatar :src="auth()->user()->avatarUrl" :alt="auth()->user()->name" size="sm" />
            </a>
            @else
            <a href="{{ route('login') }}" class="p-2 text-text-tertiary">
                <span class="material-symbols-outlined text-[28px]">login</span>
            </a>
            @endauth
        </nav>
        @endif
    </div>

    <!-- Global Toast Notification -->
    <div x-data="{ toasts: [] }" 
         @notify.window="
            let toast = { id: Date.now(), message: $event.detail.message, type: $event.detail.type };
            toasts.push(toast);
            setTimeout(() => { toasts = toasts.filter(t => t.id !== toast.id) }, 3000);
         "
         class="fixed bottom-24 lg:bottom-10 right-4 lg:right-10 z-50 flex flex-col gap-2 pointer-events-none">
        
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition.duration.300ms
                 class="px-4 py-3 rounded-xl shadow-premium border backdrop-blur-md text-sm font-medium flex items-center gap-2 pointer-events-auto"
                 :class="toast.type === 'error' ? 'bg-red-500/10 border-red-500/20 text-red-600' : 'bg-surface border-border text-text-primary'">
                
                <span class="material-symbols-outlined text-[18px]" 
                      :class="toast.type === 'error' ? 'text-red-500' : 'text-primary'"
                      x-text="toast.type === 'error' ? 'error' : 'check_circle'">
                </span>
                
                <span x-text="toast.message"></span>
            </div>
        </template>
    </div>

</body>
</html>
