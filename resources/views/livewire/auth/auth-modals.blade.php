<div>
    <!-- Login Modal -->
    @if($showLoginModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-md">
        <div class="w-11/12 max-w-md bg-base-100 rounded-xl shadow-xl overflow-hidden animate-fadeIn relative">
            <!-- Close button - Dipindahkan ke luar dari nested div dan diberi z-index -->
            <button class="btn btn-ghost btn-sm absolute right-3 top-3 z-10 text-base-content" wire:click="closeAllModals">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            
            <!-- Side decoration -->
            <div class="flex flex-col md:flex-row">
                <div class="w-1 md:w-2 bg-gradient-to-b from-primary to-primary-focus h-full"></div>
                
                <div class="flex-1 p-8">
                    <!-- Header -->
                    <div class="mb-8 text-center">
                        <h2 class="font-serif text-2xl font-light tracking-tight mb-2">Selamat Datang</h2>
                        <div class="w-12 h-0.5 bg-primary mx-auto opacity-70"></div>
                    </div>
                    
                    <form wire:submit="login" class="space-y-6">
                        <div class="space-y-4">
                            <div class="form-control">
                                <input type="email" wire:model="email" placeholder="Email" 
                                    class="input w-full bg-base-200/50 border-0 focus:bg-base-200 focus:ring-1 focus:ring-primary/30 transition-all" 
                                    required />
                                @error('email')
                                    <span class="text-error text-xs mt-1 italic">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-control">
                                <input type="password" wire:model="password" placeholder="Password" 
                                    class="input w-full bg-base-200/50 border-0 focus:bg-base-200 focus:ring-1 focus:ring-primary/30 transition-all" 
                                    required />
                                @error('password')
                                    <span class="text-error text-xs mt-1 italic">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="remember" class="checkbox checkbox-xs checkbox-primary" />
                                    <span class="text-xs text-base-content/70">Ingat saya</span>
                                </label>
                                
                                <a href="#" class="text-xs text-primary hover:text-primary-focus transition-colors">
                                    Lupa password?
                                </a>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-full normal-case font-normal text-sm">
                            Masuk
                        </button>
                    </form>
                    
                    <div class="mt-8 flex items-center gap-3">
                        <div class="h-px bg-base-300 flex-1"></div>
                        <span class="text-xs text-base-content/50 uppercase tracking-widest">atau</span>
                        <div class="h-px bg-base-300 flex-1"></div>
                    </div>
                    
                    <div class="mt-5">
                        <button class="btn btn-outline btn-sm w-full normal-case font-normal gap-2 hover:bg-base-200 border-base-300">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 488 512">
                                <path fill="currentColor" d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"/>
                            </svg>
                            Masuk dengan Google
                        </button>
                    </div>
                    
                    <p class="text-center text-xs text-base-content/70 mt-8">
                        Belum punya akun?
                        <button type="button" class="text-primary hover:underline ml-1" wire:click="switchToRegister">
                            Daftar
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Register Modal -->
    @if($showRegisterModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-md">
        <div class="w-11/12 max-w-md bg-base-100 rounded-xl shadow-xl overflow-hidden animate-fadeIn relative">
            <!-- Close button - Dipindahkan ke luar dari nested div dan diberi z-index -->
            <button class="btn btn-ghost btn-sm absolute right-3 top-3 z-10 text-base-content" wire:click="closeAllModals">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            
            <!-- Side decoration -->
            <div class="flex flex-col md:flex-row">
                <div class="w-1 md:w-2 bg-gradient-to-b from-secondary to-secondary-focus h-full"></div>
                
                <div class="flex-1 p-8">
                    <!-- Header -->
                    <div class="mb-8 text-center">
                        <h2 class="font-serif text-2xl font-light tracking-tight mb-2">Buat Akun</h2>
                        <div class="w-12 h-0.5 bg-secondary mx-auto opacity-70"></div>
                    </div>
                    
                    <form wire:submit="register" class="space-y-5 max-h-[60vh] overflow-y-auto pr-1">
                        <div class="space-y-4">
                            <div class="form-control">
                                <input type="text" wire:model="name" placeholder="Nama Lengkap" 
                                    class="input w-full bg-base-200/50 border-0 focus:bg-base-200 focus:ring-1 focus:ring-secondary/30 transition-all" 
                                    required />
                                @error('name')
                                    <span class="text-error text-xs mt-1 italic">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-control">
                                <input type="email" wire:model="email" placeholder="Email" 
                                    class="input w-full bg-base-200/50 border-0 focus:bg-base-200 focus:ring-1 focus:ring-secondary/30 transition-all" 
                                    required />
                                @error('email')
                                    <span class="text-error text-xs mt-1 italic">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-control">
                                <input type="text" wire:model="username" placeholder="Username" 
                                    class="input w-full bg-base-200/50 border-0 focus:bg-base-200 focus:ring-1 focus:ring-secondary/30 transition-all" 
                                    required />
                                @error('username')
                                    <span class="text-error text-xs mt-1 italic">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-control">
                                <input type="password" wire:model="password" placeholder="Password" 
                                    class="input w-full bg-base-200/50 border-0 focus:bg-base-200 focus:ring-1 focus:ring-secondary/30 transition-all" 
                                    required />
                                @error('password')
                                    <span class="text-error text-xs mt-1 italic">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-control">
                                <input type="password" wire:model="password_confirmation" placeholder="Konfirmasi Password" 
                                    class="input w-full bg-base-200/50 border-0 focus:bg-base-200 focus:ring-1 focus:ring-secondary/30 transition-all" 
                                    required />
                            </div>
                            
                            <div class="form-control">
                                <label class="flex items-start gap-2 cursor-pointer">
                                    <input type="checkbox" class="checkbox checkbox-xs checkbox-secondary mt-1" required />
                                    <span class="text-xs text-base-content/70 leading-tight">
                                        Dengan mendaftar, saya menyetujui <a href="#" class="text-secondary hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-secondary hover:underline">Kebijakan Privasi</a>
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-secondary w-full normal-case font-normal text-sm">
                            Daftar
                        </button>
                        
                        <p class="text-center text-xs text-base-content/70">
                            Sudah punya akun?
                            <button type="button" class="text-secondary hover:underline ml-1" wire:click="switchToLogin">
                                Masuk
                            </button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <style>
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    </style>
</div>