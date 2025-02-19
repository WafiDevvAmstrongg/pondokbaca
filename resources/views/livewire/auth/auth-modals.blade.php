<div>
    <!-- Login Modal -->
    @if($showLoginModal)
    <div class="modal modal-open">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Welcome Back!</h3>
            <form wire:submit="login">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input type="email" wire:model="email" class="input input-bordered" required />
                    @error('email')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Password</span>
                    </label>
                    <input type="password" wire:model="password" class="input input-bordered" required />
                    @error('password')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control mt-4">
                    <label class="label cursor-pointer">
                        <span class="label-text">Remember me</span>
                        <input type="checkbox" wire:model="remember" class="checkbox" />
                    </label>
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <button type="button" class="btn" wire:click="closeAllModals">Cancel</button>
                </div>
            </form>
            <p class="text-center mt-4">
                Belum punya akun? 
                <button type="button" class="text-blue-500 hover:underline" wire:click="switchToRegister">
                    Daftar di sini
                </button>
            </p>
        </div>
        <div class="modal-backdrop" wire:click="closeAllModals"></div>
    </div>
    @endif

    <!-- Register Modal -->
    @if($showRegisterModal)
    <div class="modal modal-open">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Create Your Account</h3>
            <form wire:submit="register">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Name</span>
                    </label>
                    <input type="text" wire:model="name" class="input input-bordered" required />
                    @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input type="email" wire:model="email" class="input input-bordered" required />
                    @error('email') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Username</span>
                    </label>
                    <input type="text" wire:model="username" class="input input-bordered" required />
                    @error('username') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Password</span>
                    </label>
                    <input type="password" wire:model="password" class="input input-bordered" required />
                    @error('password') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Confirm Password</span>
                    </label>
                    <input type="password" wire:model="password_confirmation" class="input input-bordered" required />
                </div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Register</button>
                    <button type="button" class="btn" wire:click="closeAllModals">Cancel</button>
                </div>
            </form>
            <p class="text-center mt-4">
                Sudah punya akun? 
                <button type="button" class="text-blue-500 hover:underline" wire:click="switchToLogin">
                    Login di sini
                </button>
            </p>
        </div>
        <div class="modal-backdrop" wire:click="closeAllModals"></div>
    </div>
    @endif
</div>