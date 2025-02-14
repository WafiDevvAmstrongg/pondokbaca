<dialog id="login_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Welcome Back!</h3>
        <form wire:submit.prevent="login">
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
                <button type="button" class="btn" onclick="login_modal.close()">Cancel</button>
            </div>
            <p class="text-center mt-4">
                Belum punya akun? 
                <button type="button" class="text-blue-500 hover:underline" onclick="switchModal('login_modal', 'register_modal')">
                    Daftar di sini
                </button>
            </p>
        </form>
    </div>
</dialog>