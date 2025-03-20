<?php

// 📌 Namespace untuk menentukan lokasi class ini dalam struktur Livewire Layouts
namespace App\Livewire\Layouts;

// 📌 Mengimpor Livewire Component untuk membuat sidebar yang dinamis
use Livewire\Component;

class Sidebar extends Component
{
    /**
     * 📌 FUNGSI RENDER
     * - Fungsi ini digunakan untuk menampilkan sidebar di halaman admin/user.
     * - Sidebar biasanya berisi menu navigasi untuk akses cepat ke berbagai fitur.
     */
    public function render()
    {
        return view('livewire.layouts.sidebar'); // 🔹 Mengembalikan tampilan sidebar dari file Blade
    }
}
