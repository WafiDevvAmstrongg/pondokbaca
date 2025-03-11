<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800">My Profile</h2>
        </div>
        <div class="p-6">
            <form wire:submit.prevent="updateProfile" class="space-y-6">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-xl overflow-hidden">
                        @if($profile_img)
                            <img src="{{ $profile_img->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                        @else
                            <img src="{{ auth()->user()->profile_img ? Storage::url(auth()->user()->profile_img) : 'https://ui-avatars.com/api/?name='.auth()->user()->name }}" 
                                 alt="Profile" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <input type="file" wire:model="profile_img" class="file-input file-input-bordered w-full max-w-xs" />
                        @error('profile_img') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Name</span>
                        </label>
                        <input type="text" wire:model="name" class="input input-bordered" required />
                        @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input type="email" wire:model="email" class="input input-bordered" required />
                    @error('email') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>

            <div class="divider my-8">Password</div>

            <form wire:submit.prevent="updatePassword" class="space-y-6">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Current Password</span>
                    </label>
                    <input type="password" wire:model="current_password" class="input input-bordered" required />
                    @error('current_password') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">New Password</span>
                        </label>
                        <input type="password" wire:model="new_password" class="input input-bordered" required />
                        @error('new_password') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Confirm New Password</span>
                        </label>
                        <input type="password" wire:model="new_password_confirmation" class="input input-bordered" required />
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </div>
</div> 