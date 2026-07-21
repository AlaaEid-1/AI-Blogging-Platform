<x-layout.app title="Join {{ config('app.name', 'Write AI') }}" :auth-layout="true" :has-sidebar="false" :has-right-sidebar="false">
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
                    <span class="material-symbols-outlined text-white text-3xl font-light">person_add</span>
                </div>
                <h2 class="mt-6 text-4xl font-extrabold tracking-tight text-text-primary">
                    Create Account
                </h2>
                <p class="mt-3 text-base text-text-secondary">
                    Join a community of thoughtful writers and readers.
                </p>
            </div>

            <div class="glass backdrop-blur-xl bg-white/60 border border-white/40 shadow-2xl rounded-3xl p-8 mt-8">
                @if (session('status'))
                    <div class="mb-6 font-medium text-sm text-green-600 bg-green-50/80 p-4 rounded-xl border border-green-200 shadow-sm flex items-center gap-2 animate-fade-in-up">
                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                        {{ session('status') }}
                    </div>
                @endif

                <form class="space-y-5" action="{{ route('register.store') }}" method="POST" x-data="{ 
                    loading: false, 
                    password: '',
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
                    
                    <div class="relative group">
                        <x-ui.label for="name" class="!mb-1.5 text-sm font-semibold">Full Name</x-ui.label>
                        <div class="relative transition-all duration-300 group-focus-within:-translate-y-0.5">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-tertiary">
                                <span class="material-symbols-outlined text-[20px]">badge</span>
                            </div>
                            <x-ui.input id="name" name="name" type="text" required value="{{ old('name') }}" placeholder="Julian Barnes" class="!pl-11 !py-3 bg-white/50 focus:bg-white transition-colors rounded-xl border-border/50" :error="$errors->has('name')" />
                        </div>
                        @error('name')
                            <span class="text-red-500 text-xs mt-1.5 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="relative group">
                        <x-ui.label for="username" class="!mb-1.5 text-sm font-semibold">Username</x-ui.label>
                        <div class="relative transition-all duration-300 group-focus-within:-translate-y-0.5">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-tertiary">
                                <span class="material-symbols-outlined text-[20px]">alternate_email</span>
                            </div>
                            <x-ui.input id="username" name="username" type="text" required value="{{ old('username') }}" placeholder="Choose a unique username" class="!pl-11 !py-3 bg-white/50 focus:bg-white transition-colors rounded-xl border-border/50" :error="$errors->has('username')" />
                        </div>
                        @error('username')
                            <span class="text-red-500 text-xs mt-1.5 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="relative group">
                        <x-ui.label for="email" class="!mb-1.5 text-sm font-semibold">Email Address</x-ui.label>
                        <div class="relative transition-all duration-300 group-focus-within:-translate-y-0.5">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-tertiary">
                                <span class="material-symbols-outlined text-[20px]">mail</span>
                            </div>
                            <x-ui.input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" placeholder="name@example.com" class="!pl-11 !py-3 bg-white/50 focus:bg-white transition-colors rounded-xl border-border/50" :error="$errors->has('email')" />
                        </div>
                        @error('email')
                            <span class="text-red-500 text-xs mt-1.5 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div x-data="{ type: 'password' }" class="relative group">
                        <x-ui.label for="password" class="!mb-1.5 text-sm font-semibold">Password</x-ui.label>
                        <div class="relative transition-all duration-300 group-focus-within:-translate-y-0.5">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-tertiary">
                                <span class="material-symbols-outlined text-[20px]">lock</span>
                            </div>
                            <x-ui.input id="password" name="password" x-bind:type="type" x-model="password" @input="checkStrength()" required placeholder="At least 8 characters" class="!pl-11 !py-3 !pr-11 bg-white/50 focus:bg-white transition-colors rounded-xl border-border/50" :error="$errors->has('password')" />
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
                        <p class="text-[10px] font-medium text-text-tertiary mt-1" x-show="password.length > 0">
                            <span x-show="strength === 1" class="text-red-500">Weak</span>
                            <span x-show="strength === 2" class="text-amber-500">Fair</span>
                            <span x-show="strength === 3" class="text-yellow-600">Good</span>
                            <span x-show="strength >= 4" class="text-green-600">Strong</span>
                        </p>
                        
                        @error('password')
                            <span class="text-red-500 text-xs mt-1.5 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-base font-bold rounded-xl text-white bg-gradient-to-r from-primary to-accent hover:shadow-[0_8px_25px_-8px_rgba(99,102,241,0.5)] transition-all duration-300 hover:-translate-y-0.5 active:scale-95 overflow-hidden" x-bind:disabled="loading">
                            <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full -translate-x-full transition-transform duration-700 ease-out skew-x-12"></div>
                            <span x-show="!loading" class="flex items-center gap-2">Create Account <span class="material-symbols-outlined text-[20px]">arrow_forward</span></span>
                            <span x-show="loading" class="flex items-center gap-2" style="display: none;">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Creating...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            
            <p class="text-center text-sm font-medium text-text-secondary mt-8">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-primary hover:text-accent transition-colors ml-1">
                    Log in
                </a>
            </p>
        </div>
    </div>
</x-layout.app>
