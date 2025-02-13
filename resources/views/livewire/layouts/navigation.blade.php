<nav class="sticky top-0 z-50 transition-all duration-300"
    :class="{ 'bg-white/80 backdrop-blur-md shadow-lg': scrolled, 'bg-transparent': !scrolled }">
    <div class="navbar max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="navbar-start">
            <div class="dropdown">
                <label tabindex="0" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </label>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="#collections">Collections</a></li>
                    <li><a href="#scholars">Scholars</a></li>
                    <li><a href="#categories">Categories</a></li>
                </ul>
            </div>
            <a class="btn btn-ghost normal-case text-xl font-amiri">PondokBaca</a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a href="#collections" class="font-medium">Collections</a></li>
                <li><a href="#scholars" class="font-medium">Scholars</a></li>
                <li><a href="#categories" class="font-medium">Categories</a></li>
            </ul>
        </div>
        <div class="navbar-end">
            @auth
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost">
                        <div class="avatar placeholder">
                            <div class="bg-[#1F4B3F] text-white rounded-full w-8">
                                <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                    </label>
                    <ul tabindex="0"
                        class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                        <li><a>My Library</a></li>
                        <li><a>Bookmarks</a></li>
                        <li><a wire:click="logout">Logout</a></li>
                    </ul>
                </div>
            @else
                <button onclick="login_modal.showModal()" class="btn btn-ghost">Login</button>
                <button onclick="register_modal.showModal()" class="btn bg-[#1F4B3F] hover:bg-[#2A6554] text-white">Join
                    Library</button>
            @endauth
        </div>
    </div>
</nav>
