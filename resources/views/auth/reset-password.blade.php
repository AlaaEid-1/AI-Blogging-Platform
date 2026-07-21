<x-layout.app title="Reset Password - {{ config('app.name', 'Write AI') }}" :auth-layout="true" :has-sidebar="false" :has-right-sidebar="false">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden bg-surface">
        
        <!-- Background Mesh -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-primary/20 blur-[120px] mix-blend-multiply"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-accent/20 blur-[120px] mix-blend-multiply"></div>
            <div class="absolute top-[20%] right-[20%] w-[20%] h-[20%] rounded-full bg-blue-400/10 blur-[80px] mix-blend-multiply"></div>
        </div>

        <div class="w-full max-w-md space-y-8 relative z-10">
            <div class="text-center">
                <div class="mx-auto w-16 h-16 bg-gradient-to-br from-primary to-accent rounded-3xl flex items-center justify-center mb-6 shadow-xl shadow-primary/20 border border-white/20 backdrop-blur-sm">
                    <span class="material-symbols-outlined text-white text-3xl font-light">lock_reset</span>
                </div>
                <h2 class="mt-6 text-4xl font-extrabold tracking-tight text-text-primary">
                    Reset Password
                </h2>
                <p class="mt-3 text-base text-text-secondary">
                    Enter your new password below.
                </p>
            </div>

            <div class="glass backdrop-blur-xl bg-white/60 border border-white/40 shadow-2xl rounded-3xl p-8 mt-8">
                <form class="space-y-5" action="{{ route('password.update') }}" method="POST" x-data="{ 
                    loading: false, 
                    password: '',
                    password_confirmation: '',
                    strength: 0,
                    checkStrength() {
                        let score = 0;
                        if (!this.password) { this.strength = 0; return; }
                        if (this.password.length > 8) score += 1;
                        if (this.password.match(/[a-z]/) && this.password.match(/[A-Z]/)) score += 1;
                        if (this.password.match(/\d/)) score += 1;
                        if (this.password.match(/[^a-zA-Z\d]/)) score += 1;
                        this.strength = score;
                    }
                }" @submit="loading = true">
                    @csrf
                    
                    <input type="hidden" name="token" value="{{ request()->route('token') }}">

                    <div class="relative group">
                        <x-ui.label for="email" class="!mb-1.5 text-sm font-semibold">Email Address</x-ui.label>
                        <div class="relative transition-all duration-300 group-focus-within:-translate-y-0.5">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-tertiary">
                                <span class="material-symbols-outlined text-[20px]">mail</span>
                            </div>
                            <x-ui.input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email', request()->email) }}" placeholder="name@example.com" class="!pl-11 !py-3 bg-white/50 focus:bg-white transition-colors rounded-xl border-border/50" :error="$errors->has('email')" readonly />
                        </div>
                        @error('email')
                            <span class="text-red-500 text-xs mt-1.5 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div x-data="{ type: 'password' }" class="relative group">
                        <x-ui.label for="password" class="!mb-1.5 text-sm font-semibold">New Password</x-ui.label>
                        <div class="relative transition-all duration-300 group-focus-within:-translate-y-0.5">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-tertiary">
                                <span class="material-symbols-outlined text-[20px]">lock</span>
                            </div>
                            <x-ui.input id="password" name="password" x-bind:type="type" x-model="password" @input="checkStrength()" required placeholder="At least 8 characters" class="!pl-11 !py-3 !pr-11 bg-white/50 focus:bg-white transition-colors rounded-xl border-border/50" :error="$errors->has('password')" autofocus />
                            <button type="button" @click="type = type === 'password' ? 'text' : 'password'" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-text-tertiary hover:text-primary transition-colors focus:outline-none">
                                <span class="material-symbols-outlined text-[20px]" x-text="type === 'password' ? 'visibility' : 'visibility_off'">visibility</span>
                            </button>
                        </div>
                        
                        <!-- Password Strength Indicator -->
                        <div class="mt-2 flex gap-1 h-1.5 w-full bg-surface-secondary rounded-full overflow-hidden">
                            <div class="h-full transition-all duration-500" x-bind:class="{
                                'w-1/4 bg-red-400': strength === 1,
                                'w-2/4 bg-amber-400': strength === 2,
                                'w-3/4 bg-yellow-400': strength === 3,
                                'w-full bg-green-500': strength >= 4,
                                'w-0': strength === 0
                            }"></div>
                        </div>
                        
                        @error('password')
                            <span class="text-red-500 text-xs mt-1.5 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div x-data="{ type: 'password' }" class="relative group">
                        <x-ui.label for="password_confirmation" class="!mb-1.5 text-sm font-semibold">Confirm Password</x-ui.label>
                        <div class="relative transition-all duration-300 group-focus-within:-translate-y-0.5">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-tertiary">
                                <span class="material-symbols-outlined text-[20px]">lock</span>
                            </div>
                            <x-ui.input id="password_confirmation" name="password_confirmation" x-bind:type="type" x-model="password_confirmation" required placeholder="••••••••" class="!pl-11 !py-3 !pr-11 bg-white/50 focus:bg-white transition-colors rounded-xl border-border/50" :error="$errors->has('password_confirmation')" />
                            <button type="button" @click="type = type === 'password' ? 'text' : 'password'" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-text-tertiary hover:text-primary transition-colors focus:outline-none">
                                <span class="material-symbols-outlined text-[20px]" x-text="type === 'password' ? 'visibility' : 'visibility_off'">visibility</span>
                            </button>
                        </div>
                        
                        <p class="text-[10px] font-medium text-text-tertiary mt-1" x-show="password_confirmation.length > 0">
                            <span x-show="password !== password_confirmation" class="text-red-500">Passwords do not match</span>
                            <span x-show="password === password_confirmation && password.length > 0" class="text-green-600">Passwords match</span>
                        </p>

                        @error('password_confirmation')
                            <span class="text-red-500 text-xs mt-1.5 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-base font-bold rounded-xl text-white bg-gradient-to-r from-primary to-accent hover:shadow-[0_8px_25px_-8px_rgba(99,102,241,0.5)] transition-all duration-300 hover:-translate-y-0.5 active:scale-95 overflow-hidden" x-bind:disabled="loading || (password_confirmation.length > 0 && password !== password_confirmation)">
                            <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full -translate-x-full transition-transform duration-700 ease-out skew-x-12"></div>
                            <span x-show="!loading" class="flex items-center gap-2">Reset Password <span class="material-symbols-outlined text-[20px]">check_circle</span></span>
                            <span x-show="loading" class="flex items-center gap-2" style="display: none;">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Updating...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</x-layout.app>