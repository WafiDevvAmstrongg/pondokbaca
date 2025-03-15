import './bootstrap';
import Alpine from 'alpinejs'

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('livewire:initialized', () => {
    Livewire.on('search-debounced', () => {
        Livewire.getByName('layouts.navigation')[0].searchBooks();
    });

    // Tutup dropdown ketika klik di luar
    document.addEventListener('click', (e) => {
        const searchContainer = document.querySelector('.search-container');
        if (searchContainer && !searchContainer.contains(e.target)) {
            Livewire.getByName('layouts.navigation')[0].closeDropdown();
        }
    });
});