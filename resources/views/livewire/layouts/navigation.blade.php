            <!-- Header -->
            <header class="bg-white border-b border-gray-100 sticky top-0 z-10">
                <div class="flex items-center justify-between px-8 py-4">
                    <!-- Search -->
                    <div class="w-[480px]">
                        <div class="relative">
                            <input type="text" placeholder="Search your favourite books"
                                class="w-full h-11 pl-11 pr-4 text-sm text-gray-700 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-white transition-colors" />
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- User Menu -->
                    @auth
                        <div class="flex items-center gap-3">
                            <button
                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>

                            <div class="dropdown dropdown-end">
                                <label tabindex="0"
                                    class="flex items-center gap-3 px-2 py-1 rounded-xl cursor-pointer hover:bg-gray-50">
                                    <div class="w-10 h-10 rounded-xl overflow-hidden">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRglLn-gRzqwE4qOH2qXiLb1bb2KlwMO5cjRA&s"
                                            alt="Profile" class="w-full h-full object-cover" />
                                    </div>
                                    <span class="font-medium">Wapek Ganteng</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </label>
                                <ul tabindex="0"
                                    class="dropdown-content menu p-2 shadow-lg bg-white rounded-xl w-52 mt-2">
                                    <li><a class="rounded-lg">Profile</a></li>
                                    <li><a class="rounded-lg">Settings</a></li>
                                    <li><a class="rounded-lg text-error">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <button onclick="login_modal.showModal()"
                            class="btn bg-[#1F4B3F] hover:bg-[#2A6554] text-white">Masuk</button>
                    @endauth
                </div>
            </header>
