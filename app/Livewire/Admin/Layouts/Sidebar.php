<?php

// 📌 Menentukan namespace agar komponen ini berada dalam struktur Livewire Admin Layouts
namespace App\Livewire\Admin\Layouts;

// 📌 Mengimpor Livewire Component untuk membuat komponen sidebar yang dinamis
use Livewire\Component;

class Sidebar extends Component
{
    /**
     * 📌 FUNGSI RENDER
     * Fungsi ini digunakan untuk menampilkan tampilan sidebar pada halaman admin.
     * Livewire akan merender file Blade yang berada di:
     * `resources/views/livewire/admin/layouts/sidebar.blade.php`
     */
    public function render()
    {
        return view('livewire.admin.layouts.sidebar'); // 🔹 Mengembalikan tampilan sidebar admin
    }
}
