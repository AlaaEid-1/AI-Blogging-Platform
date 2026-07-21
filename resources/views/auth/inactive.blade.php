<x-layout.app title="Account Inactive - {{ config('app.name') }}" :auth-layout="true" :has-sidebar="false" :has-right-sidebar="false">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md text-center">
            
            <x-ui.card className="shadow-xl shadow-surface-secondary/50 p-8 sm:p-12">
                <div class="mx-auto w-20 h-20 bg-yellow-50 rounded-full flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-yellow-500 text-4xl">hourglass_empty</span>
                </div>
                
                <h2 class="text-2xl font-extrabold text-text-primary mb-2">Account Inactive</h2>
                <p class="text-text-secondary mb-8">
                    Your account is currently inactive. Please check your email for activation instructions or contact support if you need assistance.
                </p>

                <div class="space-y-3">
                    <x-ui.button variant="primary" className="w-full justify-center" href="/">
                        Return to Home
                    </x-ui.button>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-text-secondary hover:text-primary transition-colors focus:outline-none focus:underline">
                            Log out
                        </button>
                    </form>
                </div>
            </x-ui.card>

        </div>
    </div>
</x-layout.app>
