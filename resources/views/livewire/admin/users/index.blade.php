<div>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-semibold text-gray-800">Data User</h2>
                <button wire:click="create" class="btn btn-primary w-full sm:w-auto">Tambah User</button>
            </div>
            <div class="mt-4">
                <input type="text" wire:model.live="search" placeholder="Cari users..." 
                       class="input input-bordered w-full max-w-xs" />
            </div>
        </div>
        
        @if ($showSuccessNotification)
        <div id="successNotification" class="bg-green-50 border-l-4 border-green-500 p-4 m-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ $notificationMessage }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="p-4">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b border-gray-100">
                        <th class="pb-4 font-medium text-gray-400">User</th>
                        <th class="pb-4 font-medium text-gray-400 hidden sm:table-cell">Email</th>
                        <th class="pb-4 font-medium text-gray-400 hidden lg:table-cell">Role</th>
                        <th class="pb-4 font-medium text-gray-400">Status</th>
                        <th class="pb-4 font-medium text-gray-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr>
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden">
                                    <img src="{{ $user->profile_img ?? 'https://ui-avatars.com/api/?name='.$user->name }}" 
                                         alt="{{ $user->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <span class="font-medium">{{ $user->name }}</span>
                                    <span class="block text-sm text-gray-500 sm:hidden">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 hidden sm:table-cell">{{ $user->email }}</td>
                        <td class="py-4 hidden lg:table-cell">
                            <span class="badge {{ $user->role === 'admin' ? 'badge-primary' : 'badge-secondary' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="py-4">
                            <div class="form-control">
                                <input type="checkbox" 
                                       class="toggle toggle-success"
                                       wire:click="toggleActive({{ $user->id }})"
                                       @checked($user->is_active)>
                            </div>
                        </td>
                        <td class="py-4">
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $user->id }})" class="btn btn-sm btn-ghost">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $user->id }})" class="btn btn-sm btn-ghost text-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $users->links() }}
        </div>
    </div>

    @if($showModal)
    <dialog class="modal modal-open">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">{{ $userId ? 'Edit User' : 'Tambah User' }}</h3>
            <form wire:submit="save">
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Nama</span>
                    </label>
                    <input type="text" wire:model="name" class="input input-bordered" required>
                    @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input type="email" wire:model="email" class="input input-bordered" required>
                    @error('email') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Password {{ $userId ? '(Kosongkan jika tidak ingin mengubah)' : '' }}</span>
                    </label>
                    <input type="password" wire:model="password" class="input input-bordered" {{ $userId ? '' : 'required' }}>
                    @error('password') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Role</span>
                    </label>
                    <select wire:model="role" class="select select-bordered" required>
                        <option value="user">User</option>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                    @error('role') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn" wire:click="$set('showModal', false)">Batal</button>
                </div>
            </form>
        </div>
    </dialog>
    @endif
</div>

<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('propertyUpdated', () => {
        // Tambahan untuk debugging
        console.log('User properties telah diperbarui');
    });
});
</script>