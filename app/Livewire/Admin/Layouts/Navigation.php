<?php

// 📌 Menentukan namespace agar komponen ini berada di dalam struktur Livewire Admin Layouts
namespace App\Livewire\Admin\Layouts;

// 📌 Mengimpor Auth untuk autentikasi pengguna (logout)
use Illuminate\Support\Facades\Auth;
// 📌 Mengimpor Livewire Component untuk membuat komponen interaktif di Laravel Livewire
use Livewire\Component;

class Navigation extends Component
{
    /**
     * 📌 FUNGSI LOGOUT
     * Fungsi ini digunakan untuk mengeluarkan pengguna dari sistem secara aman.
     * Langkah-langkah yang dilakukan:
     * 1️⃣ Menggunakan Auth::logout() untuk mengeluarkan pengguna yang sedang login.
     * 2️⃣ Menghapus sesi pengguna dengan session()->invalidate() agar tidak bisa digunakan kembali.
     * 3️⃣ Regenerasi token CSRF dengan session()->regenerateToken() untuk mencegah serangan CSRF.
     * 4️⃣ Mengarahkan pengguna ke halaman utama ('/') setelah logout berhasil.
     */
    public function logout()
    {
        Auth::logout(); // 🔹 Mengeluarkan pengguna dari sesi login

        session()->invalidate(); // 🔹 Menghapus semua data sesi pengguna untuk keamanan

        session()->regenerateToken(); // 🔹 Menghasilkan token CSRF baru agar lebih aman dari serangan CSRF

        return redirect('/'); // 🔹 Mengarahkan pengguna kembali ke halaman utama setelah logout
    }
    
    /**
     * 📌 FUNGSI RENDER
     * Fungsi ini digunakan untuk menampilkan tampilan navigasi admin.
     * Livewire akan merender file Blade yang berada di `resources/views/livewire/admin/layouts/navigation.blade.php`
     */
    public function render()
    {
        return view('livewire.admin.layouts.navigation'); // 🔹 Mengembalikan tampilan navigasi admin
    }
}
