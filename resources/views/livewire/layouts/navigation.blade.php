<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <h1 class="text-xl font-bold">{{ config('app.name', 'Laravel') }}</h1>
                </div>
            </div>
            
            <div class="flex items-center">
                @auth
                    <div x-data="{ open: false }" class="ml-3 relative">
                        <button @click="open = !open" class="btn btn-ghost">
                            {{ Auth::user()->name }}
                        </button>
                        <div x-show="open" 
                             @click.away="open = false"
                             class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <button wire:click="logout" class="btn btn-ghost w-full text-left px-4 py-2">
                                Logout
                            </button>
                        </div>
                    </div>
                @else
                    <div class="space-x-2">
                        <button onclick="login_modal.showModal()" class="btn btn-primary">Login</button>
                        <button onclick="register_modal.showModal()" class="btn btn-secondary">Register</button>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>