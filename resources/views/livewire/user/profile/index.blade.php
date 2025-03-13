<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Profil Pengguna</h1>
        <p class="text-gray-600">Perbarui informasi profil dan pengaturan akun Anda</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Informasi Profil</h2>
        
        <!-- Profile Update Notification -->
        @if($showProfileNotification)
        <div id="profileNotification" class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        Profil berhasil diperbarui!
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        <form wire:submit="updateProfile" class="space-y-4">
            <div class="flex flex-col md:flex-row md:gap-8">
                <!-- Profile Image -->
                <div class="mb-4 md:mb-0">
                    <div class="w-32 h-32 rounded-xl overflow-hidden mb-3">
                        @if ($profile_img)
                            <img src="{{ Storage::url('profiles/' . $profile_img) }}" alt="Profile Image" class="w-full h-full object-cover" />
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <label for="profileImage" class="btn btn-sm bg-gray-100 hover:bg-gray-200 text-gray-700 border-0">
                        Ganti Foto
                    </label>
                    <input type="file" wire:model.live="newProfileImage" id="profileImage" class="hidden" accept="image/*" />
                    
                    @error('newProfileImage')
                        <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror

                    @if ($newProfileImage)
                        <p class="text-sm text-primary mt-2">Foto baru dipilih</p>
                    @endif
                </div>
                
                <!-- Profile Form -->
                <div class="flex-1 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" id="name" class="w-full h-11 px-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-white transition-colors" />
                        @error('name')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="email" id="email" class="w-full h-11 px-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-white transition-colors" />
                        @error('email')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="pt-2">
                        <button type="submit" class="btn bg-[#1F4B3F] hover:bg-[#2A6554] text-white">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold mb-4">Ubah Password</h2>
        
        <!-- Password Update Notification -->
        @if($showPasswordNotification)
        <div id="passwordNotification" class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        Password berhasil diperbarui!
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        <form wire:submit="updatePassword" class="space-y-4 max-w-md">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                <input type="password" wire:model="current_password" id="current_password" class="w-full h-11 px-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-white transition-colors" />
                @error('current_password')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <input type="password" wire:model="new_password" id="new_password" class="w-full h-11 px-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-white transition-colors" />
                @error('new_password')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" wire:model="new_password_confirmation" id="new_password_confirmation" class="w-full h-11 px-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-white transition-colors" />
            </div>
            
            <div class="pt-2">
                <button type="submit" class="btn bg-[#1F4B3F] hover:bg-[#2A6554] text-white">
                    Perbarui Password
                </button>
            </div>
        </form>
    </div>
    
    <!-- Scripts for handling notifications -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Auto-hide profile notification after 3 seconds
            Livewire.on('hideProfileNotification', () => {
                setTimeout(() => {
                    @this.showProfileNotification = false;
                }, 3000);
            });
            
            // Auto-hide password notification after 3 seconds
            Livewire.on('hidePasswordNotification', () => {
                setTimeout(() => {
                    @this.showPasswordNotification = false;
                }, 3000);
            });
        });
    </script>
</div>