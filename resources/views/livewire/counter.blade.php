<div x-data="{ open: false }" class="text-center">
    <h1 class="text-2xl font-bold">Counter: {{ $count }}</h1>
    
    <button wire:click="increment" class="bg-blue-500 text-white px-4 py-2 rounded">
        Tambah
    </button>

    <button @click="open = !open" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">
        Toggle Teks
    </button>

    <p x-show="open" class="mt-2">Teks ini bisa di-toggle dengan Alpine.js</p>
</div>
