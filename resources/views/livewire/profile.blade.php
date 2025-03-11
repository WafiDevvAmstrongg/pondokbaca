<div class="p-4 bg-white shadow rounded-lg">
    <h2 class="text-lg font-semibold">Edit Profil</h2>

    {{-- Notifikasi Sukses --}}
    @if (session()->has('message'))
        <div class="mt-2 p-2 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    {{-- Foto Profil --}}
    <div class="mt-4 text-center">
        @if ($newPhoto)
            <img src="{{ $newPhoto->temporaryUrl() }}" class="w-24 h-24 rounded-full mx-auto">
        @elseif ($photo)
            <img src="{{ asset('storage/' . $photo) }}" class="w-24 h-24 rounded-full mx-auto">
        @else
            <img src="https://via.placeholder.com/100" class="w-24 h-24 rounded-full mx-auto">
        @endif
    </div>

    {{-- Input Nama --}}
    <div class="mt-4">
        <label class="block">Nama:</label>
        <input type="text" wire:model.defer="name" class="border p-2 w-full rounded">
        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Input Email --}}
    <div class="mt-4">
        <label class="block">Email:</label>
        <input type="email" wire:model.defer="email" class="border p-2 w-full rounded">
        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Upload Foto Profil --}}
    <div class="mt-4">
        <label class="block">Foto Profil:</label>
        <input type="file" wire:model="newPhoto" class="border p-2 w-full rounded">
        @error('newPhoto') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Tombol Simpan --}}
    <button wire:click="updateProfile" wire:loading.attr="disabled" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
        Simpan
    </button>

    {{-- Loading Indicator --}}
    <div wire:loading class="text-blue-500 mt-2">Menyimpan...</div>
</div>
