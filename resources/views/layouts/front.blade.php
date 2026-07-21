<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>{{ $title }}</title>
    {{--
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Source+Serif+4:wght@400;600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />

    <script>
        const USER_ID = "{{ auth()->id() }}"
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-error-container": "#93000a",
                        "on-primary": "#ffffff",
                        "inverse-on-surface": "#f1f1f1",
                        "tertiary-container": "#a15100",
                        "outline": "#7b7487",
                        "tertiary-fixed-dim": "#ffb784",
                        "on-secondary-fixed-variant": "#474746",
                        "on-primary-fixed": "#25005a",
                        "inverse-primary": "#d2bbff",
                        "surface-variant": "#e2e2e2",
                        "on-tertiary-fixed-variant": "#713700",
                        "outline-variant": "#ccc3d8",
                        "secondary-fixed": "#e5e2e1",
                        "on-secondary": "#ffffff",
                        "on-surface-variant": "#4a4455",
                        "primary": "#630ed4",
                        "secondary": "#5f5e5e",
                        "on-secondary-fixed": "#1c1b1b",
                        "on-primary-fixed-variant": "#5a00c6",
                        "surface-container-lowest": "#ffffff",
                        "surface-tint": "#732ee4",
                        "surface-container-low": "#f3f3f3",
                        "on-primary-container": "#ede0ff",
                        "secondary-fixed-dim": "#c8c6c5",
                        "surface": "#f9f9f9",
                        "error": "#ba1a1a",
                        "inverse-surface": "#2f3131",
                        "tertiary": "#7d3d00",
                        "surface-container": "#eeeeee",
                        "surface-bright": "#f9f9f9",
                        "on-tertiary-container": "#ffe0cd",
                        "secondary-container": "#e2dfde",
                        "surface-container-high": "#e8e8e8",
                        "on-background": "#1a1c1c",
                        "on-surface": "#1a1c1c",
                        "primary-fixed-dim": "#d2bbff",
                        "primary-fixed": "#eaddff",
                        "surface-container-highest": "#e2e2e2",
                        "surface-dim": "#dadada",
                        "error-container": "#ffdad6",
                        "on-tertiary": "#ffffff",
                        "on-error": "#ffffff",
                        "tertiary-fixed": "#ffdcc6",
                        "on-secondary-container": "#636262",
                        "background": "#f9f9f9",
                        "primary-container": "#7c3aed",
                        "on-tertiary-fixed": "#301400"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-mobile": "1rem",
                        "container-max": "1200px",
                        "gutter": "1.5rem",
                        "article-max": "720px",
                        "section-gap": "4rem"
                    },
                    "fontFamily": {
                        "body-lg": ["Source Serif 4"],
                        "ui-button": ["Inter"],
                        "display-lg": ["Source Serif 4"],
                        "headline-md": ["Source Serif 4"],
                        "body-md": ["Source Serif 4"],
                        "display-lg-mobile": ["Source Serif 4"],
                        "metadata": ["Inter"],
                        "ui-label": ["Inter"]
                    },
                    "fontSize": {
                        "body-lg": ["20px", { "lineHeight": "1.6", "fontWeight": "400" }],
                        "ui-button": ["16px", { "lineHeight": "1", "letterSpacing": "0.02em", "fontWeight": "600" }],
                        "display-lg": ["48px", { "lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "headline-md": ["32px", { "lineHeight": "1.3", "fontWeight": "600" }],
                        "body-md": ["18px", { "lineHeight": "1.6", "fontWeight": "400" }],
                        "display-lg-mobile": ["32px", { "lineHeight": "1.2", "fontWeight": "700" }],
                        "metadata": ["12px", { "lineHeight": "1.4", "fontWeight": "400" }],
                        "ui-label": ["14px", { "lineHeight": "1.4", "letterSpacing": "0.01em", "fontWeight": "500" }]
                    }
                },
            },
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

    {{ $style ?? '' }}
    {{ $headScripts ?? '' }}
</head>

<body class="font-body-md text-body-md selection:bg-primary-fixed selection:text-on-primary-fixed">
    <!-- TopNavBar -->
    <header class="fixed top-0 z-50 w-full bg-surface border-b border-outline-variant">
        <div class="flex justify-between items-center w-full px-gutter max-w-container-max mx-auto h-16">
            <div class="flex items-center gap-8">
                <a class="font-display-lg-mobile text-display-lg-mobile font-bold text-on-surface"
                    href="{{ route('home') }}">{{ config('app.name') }}</a>
                <nav class="hidden md:flex items-center gap-6">
                    @section('nav')
                    <a class="text-primary font-bold border-b-2 border-primary pb-1 font-ui-label text-ui-label hover:text-primary transition-colors duration-200"
                        href="#">Feed</a>
                    @can('users.manage')
                    <a class="text-on-surface-variant font-medium font-ui-label text-ui-label hover:text-primary transition-colors duration-200"
                        href="#">Authors</a>
                    @endcan
                    <a class="text-on-surface-variant font-medium font-ui-label text-ui-label hover:text-primary transition-colors duration-200"
                        href="#">Dashboard</a>
                    @show
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <div
                    class="hidden lg:flex items-center bg-surface-container border border-outline-variant rounded-full px-4 py-1.5 gap-2">
                    <span class="material-symbols-outlined text-secondary" data-icon="search">search</span>
                    <input class="bg-transparent border-none focus:ring-0 text-ui-label font-ui-label w-48"
                        placeholder="Search..." type="text" />
                </div>
                <div class="flex items-center gap-2" x-data="{ notifOpen: false }">

                    <!-- 🔔 Notifications -->
<div class="relative">

    <button
        @click="notifOpen = !notifOpen"
        class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-all relative">

        <span class="material-symbols-outlined">notifications</span>

        <!-- Red dot for unread -->
        @auth
            @if(auth()->user()->unreadNotifications->count())
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            @endif
        @endauth

    </button>

    <!-- Dropdown -->
    <div
        x-show="notifOpen"
        x-cloak
        @click.away="notifOpen = false"
        x-transition
        class="absolute right-0 mt-3 w-80 bg-white shadow-lg rounded-xl border z-50 overflow-hidden">

        <!-- Header -->
        <div class="p-3 border-b text-sm font-semibold">
            Notifications
        </div>

        <!-- Notifications -->
        <div class="max-h-72 overflow-y-auto">

            @auth
                @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)

                    <div class="flex gap-3 p-3 hover:bg-gray-50 border-b">

                        <!-- Avatar -->
                        <img class="w-9 h-9 rounded-full object-cover"
                             src="{{ $notification->data['meta']['follower_avatar'] ?? asset('images/default-avatar.png') }}">

                        <!-- Content -->
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">
                                {{ $notification->data['body'] }}
                            </p>

                            <span class="text-xs text-gray-400">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>

                    </div>

                @empty
                    <div class="p-4 text-sm text-center text-gray-500">
                        No notifications
                    </div>
                @endforelse
            @endauth

        </div>

        <!-- Footer -->
        <a href="{{ route('dashboard.notifications.index') }}"
           class="block text-center p-3 text-sm text-blue-500 hover:bg-gray-50">
            View all
        </a>

    </div>
</div>

    <!-- 🔖 Bookmark -->
    <button
        class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-all">
        <span class="material-symbols-outlined">bookmark</span>
    </button>

    <!-- 👤 User Menu -->
    <x-user-menu />

</div>
            </div>
        </div>
    </header>
    <!-- Main Content Layout -->
    <main class="{{ $mainClass ?? '' }}">
        {{ $slot }}
    </main>
    <!-- Footer -->
    <footer class="bg-surface border-t border-outline-variant">
        <div
            class="w-full py-section-gap px-gutter max-w-container-max mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex flex-col gap-2 items-center md:items-start">
                <span class="font-headline-md text-headline-md text-on-surface">Ink &amp; Paper</span>
                <p class="font-metadata text-metadata text-secondary">© 2024 Ink &amp; Paper Platform. All rights
                    reserved.</p>
            </div>
            <nav class="flex flex-wrap justify-center gap-8">
                <a class="text-secondary font-metadata text-metadata hover:text-on-surface underline transition-all"
                    href="#">About</a>
                <a class="text-secondary font-metadata text-metadata hover:text-on-surface underline transition-all"
                    href="#">Privacy</a>
                <a class="text-secondary font-metadata text-metadata hover:text-on-surface underline transition-all"
                    href="#">Terms</a>
                <a class="text-secondary font-metadata text-metadata hover:text-on-surface underline transition-all"
                    href="#">API</a>
                <a class="text-secondary font-metadata text-metadata hover:text-on-surface underline transition-all"
                    href="#">Help</a>
            </nav>
            <div class="flex gap-4">
                <button
                    class="p-2 text-secondary hover:text-primary transition-colors focus:outline-none ring-primary"><span
                        class="material-symbols-outlined">alternate_email</span></button>
                <button
                    class="p-2 text-secondary hover:text-primary transition-colors focus:outline-none ring-primary"><span
                        class="material-symbols-outlined">rss_feed</span></button>
            </div>
        </div>
    </footer>
</body>

</html>
