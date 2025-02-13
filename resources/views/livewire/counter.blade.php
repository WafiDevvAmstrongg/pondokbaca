<div x-data="{ open: false }" class="text-center">

    <button @click="open = !open" class="bg-gray-500 text-white px-4 py-2 rounded mt-2">
        Toggle Teks
    </button>
    <p x-show="open" class="mt-2">Teks ini bisa di-toggle dengan Alpine.js</p>
</div>
