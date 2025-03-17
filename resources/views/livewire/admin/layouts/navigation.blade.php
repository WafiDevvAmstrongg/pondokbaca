<header class="bg-white border-b border-gray-100 sticky top-0 z-10">
    <div class="flex items-center justify-between px-8 py-4">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        
        <div class="flex items-center gap-3">

            <div class="dropdown dropdown-end">
                <label tabindex="0" class="flex items-center gap-3 px-2 py-1 rounded-xl cursor-pointer hover:bg-gray-50">
                    <div class="w-10 h-10 rounded-xl overflow-hidden">
                        <img src="{{ auth()->user()->profile_img ?? 'https://ui-avatars.com/api/?name='.auth()->user()->name }}" 
                             alt="Profile" class="w-full h-full object-cover" />
                    </div>
                    <span class="font-medium">{{ auth()->user()->name }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </label>
                <ul tabindex="0" class="dropdown-content menu p-2 shadow-lg bg-white rounded-xl w-52 mt-2">
                    <li><a class="rounded-lg">Profile</a></li>
                    <li>
                        <button wire:click="logout" class="rounded-lg text-error w-full text-left">Logout</button>
                    </li>                            
                </ul>
            </div>
        </div>
    </div>
</header>