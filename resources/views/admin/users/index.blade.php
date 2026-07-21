<x-layout.app title="User Management" :has-sidebar="true" :has-right-sidebar="false">
    <div class="px-4 py-8 lg:px-8 max-w-7xl mx-auto">
        
        <!-- Header Section -->
        @if (session()->has('status'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 flex items-center gap-2 text-sm font-medium shadow-sm">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                {{ session()->get('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium space-y-1 shadow-sm">
                <div class="flex items-center gap-2 text-red-900 font-bold mb-2">
                    <span class="material-symbols-outlined text-[18px]">error</span>
                    Please fix the following errors:
                </div>
                <ul class="list-disc pl-6 space-y-1">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div>
                <x-ui.section-title 
                    title="User Management" 
                    description="Manage user accounts, assign multiple roles, and control access."
                />
            </div>
            <!-- Add User (Coming Soon) -->
            <x-ui.button type="button" variant="primary" class="shrink-0 flex items-center gap-2 opacity-50 cursor-not-allowed" title="Coming Soon" disabled>
                <span class="material-symbols-outlined text-[20px]">person_add</span>
                Invite User
            </x-ui.button>
        </div>

        <div class="glass backdrop-blur-xl bg-white/60 border border-border/40 shadow-xl shadow-surface-secondary/20 rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-text-secondary">
                    <thead class="bg-surface-secondary/50 text-xs uppercase font-bold text-text-tertiary tracking-wider border-b border-border/50">
                        <tr>
                            <th scope="col" class="px-6 py-4">User</th>
                            <th scope="col" class="px-6 py-4">Roles</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                            <th scope="col" class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/50">
                        @forelse ($users as $user)
                            <tr class="hover:bg-surface-hover/50 transition-colors group" x-data="{ editing: false }">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <x-ui.avatar :src="$user->avatarUrl ?? asset('images/avatars/blank.png')" :alt="$user->name" size="md" />
                                        <div>
                                            <div class="font-bold text-text-primary">{{ $user->name }}</div>
                                            <div class="text-xs text-text-tertiary">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5 max-w-[250px]">
                                        @forelse($user->roles as $role)
                                            <span class="px-2 py-0.5 bg-primary/10 text-primary border border-primary/20 rounded-md text-[11px] font-bold uppercase tracking-wide">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="text-[11px] font-medium text-text-tertiary">No Roles</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                        </span>
                                    @elseif($user->status === 'suspended')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Suspended
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="editing = true" class="text-text-tertiary hover:text-primary transition-colors p-2 rounded-lg hover:bg-primary/10 focus:outline-none focus:ring-2 focus:ring-primary/20">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                </td>
                                
                                <!-- Edit User Modal (Alpine) -->
                                <template x-if="editing">
                                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" style="display: none;" x-show="editing">
                                        <!-- Backdrop -->
                                        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" @click="editing = false" x-transition.opacity></div>
                                        
                                        <!-- Modal Panel -->
                                        <div class="relative bg-surface rounded-3xl border border-border shadow-2xl w-full max-w-md overflow-hidden" 
                                             x-transition:enter="transition ease-out duration-300" 
                                             x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
                                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-200"
                                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                                            
                                            <div class="px-6 py-5 border-b border-border flex items-center justify-between bg-surface-secondary/30">
                                                <h3 class="text-lg font-bold text-text-primary">Edit User</h3>
                                                <button @click="editing = false" class="text-text-tertiary hover:text-text-primary transition-colors">
                                                    <span class="material-symbols-outlined">close</span>
                                                </button>
                                            </div>
                                            
                                            <form method="POST" action="{{ route('users.update', $user) }}" class="p-6 space-y-6">
                                                @csrf
                                                @method('PUT')
                                                
                                                <!-- User Info Readonly -->
                                                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-hover border border-border/50">
                                                    <x-ui.avatar :src="$user->avatarUrl ?? asset('images/avatars/blank.png')" :alt="$user->name" size="sm" />
                                                    <div>
                                                        <div class="font-bold text-text-primary text-sm">{{ $user->name }}</div>
                                                        <div class="text-xs text-text-tertiary">{{ $user->email }}</div>
                                                    </div>
                                                </div>

                                                <!-- Status Selector -->
                                                <div>
                                                    <label class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">Account Status</label>
                                                    <select name="status" class="w-full px-4 py-2.5 bg-surface border border-border/60 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-text-primary shadow-sm appearance-none">
                                                        <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                        <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                                    </select>
                                                </div>

                                                <!-- Roles Selector -->
                                                <div>
                                                    <label class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">Assigned Roles</label>
                                                    <div class="bg-surface-hover/50 rounded-xl border border-border/60 p-2 max-h-48 overflow-y-auto divide-y divide-border/40">
                                                        @foreach($roles as $role)
                                                            <label class="flex items-center gap-3 p-2.5 hover:bg-surface-hover transition-colors cursor-pointer rounded-lg group">
                                                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                                                    {{ $user->roles->contains($role->id) ? 'checked' : '' }}
                                                                    class="w-4 h-4 rounded border-border/60 text-primary focus:ring-primary bg-surface cursor-pointer">
                                                                <span class="text-sm font-medium text-text-primary group-hover:text-primary transition-colors">{{ $role->name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- Actions -->
                                                <div class="flex items-center gap-3 pt-4 border-t border-border">
                                                    <button type="button" @click="editing = false" class="flex-1 px-4 py-2 text-sm font-bold text-text-secondary bg-surface border border-border/60 hover:bg-surface-hover rounded-xl transition-colors">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="flex-1 px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-primary to-accent hover:shadow-lg hover:-translate-y-0.5 rounded-xl transition-all">
                                                        Save Changes
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </template>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-text-secondary">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <span class="material-symbols-outlined text-[48px] text-text-tertiary opacity-50">group_off</span>
                                        <p class="text-sm">No users found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-border/50 bg-surface-secondary/30">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layout.app>
