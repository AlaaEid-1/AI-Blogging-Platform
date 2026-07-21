<x-layout.app title="Roles & Permissions" :has-sidebar="true" :has-right-sidebar="false">
    <div class="px-4 py-8 lg:px-8 max-w-5xl mx-auto" x-data="{ showModal: {{ $editRole || $errors->any() ? 'true' : 'false' }} }">
        
        <!-- Header Section -->
        @if (session()->has('status'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 flex items-center gap-2 text-sm font-medium">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                {{ session()->get('status') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-4 border-b border-border/50">
            <div>
                <h1 class="text-2xl font-bold text-text-primary">Access Control</h1>
                <p class="text-text-secondary text-sm mt-1">Manage how permissions are grouped into roles.</p>
            </div>
            
            <button @click="showModal = true; window.history.replaceState({}, '', '{{ route('roles.index') }}')" class="shrink-0 flex items-center gap-2 bg-text-primary text-white hover:bg-text-secondary px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <span class="material-symbols-outlined text-[18px]">add</span>
                New Role
            </button>
        </div>

        <!-- Roles Table -->
        <div class="bg-surface border border-border/60 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-text-secondary">
                    <thead class="bg-surface-secondary/50 text-xs font-semibold text-text-tertiary uppercase tracking-wider border-b border-border/50">
                        <tr>
                            <th scope="col" class="px-6 py-4">Role Name</th>
                            <th scope="col" class="px-6 py-4">Assigned Users</th>
                            <th scope="col" class="px-6 py-4">Permissions</th>
                            <th scope="col" class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/50">
                        @forelse ($roles as $role)
                            <tr class="hover:bg-surface-hover/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-text-primary">{{ $role->name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 text-text-secondary">
                                        <span class="material-symbols-outlined text-[16px] text-text-tertiary">group</span>
                                        {{ $role->users_count }} Users
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-text-secondary">
                                        {{ count($role->abilities ?? []) }} permissions
                                    </div>
                                    @if(count($role->abilities ?? []) > 0)
                                        <div class="mt-1 text-[11px] text-text-tertiary font-mono truncate max-w-xs">
                                            {{ implode(', ', array_slice($role->abilities ?? [], 0, 3)) }}{{ count($role->abilities ?? []) > 3 ? '...' : '' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('roles.index', ['edit' => $role->id]) }}" class="text-text-tertiary hover:text-primary transition-colors text-sm">
                                            Edit
                                        </a>
                                        
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-text-tertiary hover:text-red-500 transition-colors text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-text-secondary">
                                    <p class="text-sm">No roles defined yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <template x-if="showModal">
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" style="display: none;" x-show="showModal">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" 
                     @click="showModal = false; @if(!$errors->any()) window.location.href = '{{ route('roles.index') }}' @endif" 
                     x-transition.opacity></div>
                
                <!-- Modal Panel -->
                <div class="relative bg-surface rounded-2xl border border-border shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                    
                    <div class="px-6 py-4 border-b border-border flex items-center justify-between shrink-0 bg-surface-secondary/30">
                        <div>
                            <h3 class="text-lg font-bold text-text-primary">{{ $editRole ? 'Edit Role: ' . $editRole->name : 'Create New Role' }}</h3>
                        </div>
                        <button @click="showModal = false; @if(!$errors->any()) window.location.href = '{{ route('roles.index') }}' @endif" class="text-text-tertiary hover:text-text-primary transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <form method="POST" action="{{ $editRole ? route('roles.update', $editRole) : route('roles.store') }}" class="flex flex-col overflow-hidden h-full">
                        @csrf
                        @if ($editRole)
                            @method('PUT')
                        @endif

                        <div class="p-6 overflow-y-auto space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-text-primary mb-2">Role Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $editRole->name ?? '') }}" required
                                    class="w-full px-4 py-2 bg-surface border border-border/80 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-text-tertiary text-sm text-text-primary" 
                                    placeholder="e.g. Content Editor">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-semibold text-text-primary">Permissions</label>
                                    <span class="text-xs text-text-tertiary">{{ count(config('abilities') ?? []) }} total</span>
                                </div>
                                
                                <div class="bg-surface border border-border/80 rounded-lg divide-y divide-border/50">
                                    @foreach (config('abilities') ?? [] as $key => $label)
                                        <label class="flex items-center gap-3 p-3 hover:bg-surface-hover/50 transition-colors cursor-pointer group">
                                            <input type="checkbox" name="abilities[]" value="{{ $key }}"
                                                @checked(is_array(old('abilities', $editRole->abilities ?? [])) && in_array($key, old('abilities', $editRole->abilities ?? [])))
                                                class="w-4 h-4 rounded border-border/80 text-primary focus:ring-primary focus:ring-offset-0 bg-surface cursor-pointer text-primary border-2">
                                            <span class="text-sm font-medium text-text-secondary group-hover:text-text-primary transition-colors">{{ $key }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('abilities')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-border shrink-0 bg-surface-secondary/30 flex justify-end gap-3">
                            <button type="button" @click="showModal = false; @if(!$errors->any()) window.location.href = '{{ route('roles.index') }}' @endif" class="px-4 py-2 text-sm font-medium text-text-secondary bg-surface border border-border hover:bg-surface-hover rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-text-primary hover:bg-text-secondary rounded-lg transition-colors">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-layout.app>
