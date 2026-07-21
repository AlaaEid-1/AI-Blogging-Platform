<x-layout.app title="Account Suspended - {{ config('app.name') }}" :auth-layout="true" :has-sidebar="false" :has-right-sidebar="false">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md text-center">
            
            <x-ui.card className="shadow-xl shadow-surface-secondary/50 p-8 sm:p-12 border-red-100">
                <div class="mx-auto w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-red-500 text-4xl" style="font-variation-settings: 'FILL' 1;">block</span>
                </div>
                
                <h2 class="text-2xl font-extrabold text-text-primary mb-2">Account Suspended</h2>
                <p class="text-text-secondary mb-8">
                    Your account has been suspended due to a violation of our terms of service. Please contact our support team for more information.
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
