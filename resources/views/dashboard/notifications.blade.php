<x-layout.app title="Notifications" :has-sidebar="true" :has-right-sidebar="false">
    <div class="max-w-4xl mx-auto px-4 py-8 md:py-12">
        
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-text-primary tracking-tight mb-2">Notifications</h1>
                <p class="text-sm text-text-secondary">Stay updated with your latest interactions and community activity.</p>
            </div>
            
            <div class="flex gap-4">
                <form method="POST" action="{{ route('dashboard.notifications.markAllUnRead') }}">
                    @csrf
                    @method('PATCH')
                    <button class="text-sm font-medium text-text-tertiary hover:text-text-primary transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">mark_email_unread</span>
                        Mark all unread
                    </button>
                </form>
                <form method="POST" action="{{ route('dashboard.notifications.markAllRead') }}">
                    @csrf
                    @method('PATCH')
                    <button class="text-sm font-medium text-primary hover:text-accent transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">done_all</span>
                        Mark all read
                    </button>
                </form>
            </div>
        </div>

        <div class="mb-8"></div>

        <!-- Notification List -->
        <div class="space-y-10">
            <section>
                <h2 class="text-xs font-bold text-text-tertiary uppercase tracking-widest mb-4 ml-2">Recent</h2>
                
                <div class="space-y-2">
                    @forelse ($notifications as $notification)
                        <div class="group relative flex items-start gap-4 p-4 rounded-xl {{ $notification->unread() ? 'bg-primary/5 border border-primary/10' : 'hover:bg-surface-secondary border border-transparent' }} transition-all cursor-pointer">
                            
                            <!-- Unread Indicator -->
                            @if ($notification->unread())
                                <div class="absolute top-1/2 -left-1.5 -translate-y-1/2 w-3 h-3 bg-primary rounded-full shadow-sm"></div>
                            @endif

                            <!-- Avatar -->
                            <div class="relative shrink-0 mt-1">
                                <x-ui.avatar src="{{ $notification->data['meta']['follower_avatar'] ?? asset('images/avatars/blank.png') }}" alt="Avatar" size="md" />
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-surface rounded-full flex items-center justify-center shadow-sm border border-border/50">
                                    <span class="material-symbols-outlined text-[12px] text-accent" style="font-variation-settings: 'FILL' 1;">
                                        favorite
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0 pr-12">
                                <p class="text-sm text-text-primary leading-snug">
                                    {{ $notification->data['body'] ?? 'New Notification' }}
                                </p>
                                <span class="text-xs text-text-tertiary mt-1.5 block flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">schedule</span>
                                    {{ $notification->created_at?->diffForHumans() ?? 'Unknown time' }}
                                </span>
                            </div>

                            <!-- Actions (Hover) -->
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity bg-surface/90 backdrop-blur-sm p-1.5 rounded-lg border border-border/50 shadow-sm">
                                @if ($notification->unread())
                                    <form method="POST" action="{{ route('dashboard.notifications.read', $notification->id) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="p-1.5 text-text-secondary hover:text-primary rounded-md hover:bg-primary/10 transition-colors" title="Mark as read">
                                            <span class="material-symbols-outlined text-[18px]">drafts</span>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('dashboard.notifications.unread', $notification->id) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="p-1.5 text-text-secondary hover:text-primary rounded-md hover:bg-primary/10 transition-colors" title="Mark as unread">
                                            <span class="material-symbols-outlined text-[18px]">mark_email_unread</span>
                                        </button>
                                    </form>
                                @endif
                                
                                <form method="POST" action="{{ route('dashboard.notifications.destroy', $notification->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="p-1.5 text-text-secondary hover:text-red-500 rounded-md hover:bg-red-50 transition-colors" title="Delete">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <x-ui.empty-state 
                            icon="notifications_off" 
                            title="All caught up" 
                            description="You have no new notifications at this time. Check back later!" 
                        />
                    @endforelse
                </div>
            </section>
        </div>

        @if($notifications->count() > 0)
            <div class="mt-12 flex flex-col items-center justify-center py-6 border-t border-border border-dashed">
                <span class="material-symbols-outlined text-text-tertiary mb-2 opacity-50" data-icon="history_edu">history_edu</span>
                <p class="text-xs text-text-tertiary uppercase tracking-wider font-semibold">End of recent activity</p>
            </div>
        @endif
        
    </div>
</x-layout.app>
