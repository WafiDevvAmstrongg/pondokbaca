<?php

// 📌 Mengimpor library yang diperlukan untuk routing dan autentikasi
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =========================================================
// 📌 RUTE UNTUK PENGGUNA UMUM (FRONTEND)
// =========================================================

// 📌 Rute halaman utama (home)
Route::get('/', \App\Livewire\Home\Home::class)->name('home');

// =========================================================
// 📌 RUTE UNTUK PENGGUNA TAMU (GUEST)
// =========================================================
Route::middleware('guest')->group(function () {
    // 📌 Rute-rute yang hanya bisa diakses oleh pengguna yang belum login
    // (Saat ini kosong, bisa diisi dengan rute login atau register)
});

// =========================================================
// 📌 RUTE UNTUK PENGGUNA YANG SUDAH LOGIN (USER)
// =========================================================
Route::middleware('auth')->group(function () {
    // 🔹 LOGOUT USER
    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // 🔹 DASHBOARD ADMIN (Hanya bisa diakses oleh admin)
    Route::get('/admin/dashboard', \App\Livewire\Admin\Dashboard::class)
        ->middleware('auth') // Pastikan user sudah login
        ->name('admin.dashboard');

    // 🔹 PROFIL PENGGUNA
    Route::get('/profile', \App\Livewire\User\Profile\Index::class)
        ->name('profile');

    // 🔹 BUKU FAVORIT PENGGUNA
    Route::get('/favorites', \App\Livewire\User\Favorit\Index::class)
        ->name('favorites');

    // 🔹 DAFTAR PEMINJAMAN PENGGUNA
    Route::get('/my-loans', \App\Livewire\User\Peminjamans\Index::class)
        ->name('my-loans');

    // 🔹 PROSES CHECKOUT PEMINJAMAN
    Route::get('/checkout/{token}', \App\Livewire\User\Checkout::class)
        ->name('user.checkout');

    // 🔹 HALAMAN PEMINJAMAN BUKU
    Route::get('/peminjaman', App\Livewire\User\Peminjamans\Index::class)
        ->name('user.peminjaman');

    // 🔹 HALAMAN PEMBAYARAN DENDA
    Route::get('/pembayaran', App\Livewire\User\Pembayaran\Index::class)
        ->name('user.pembayaran');
});

// =========================================================
// 📌 RUTE UNTUK ADMIN (BACKEND)
// =========================================================
Route::middleware(['auth', 'role:admin']) // Hanya admin yang bisa mengakses
    ->prefix('admin') // Semua rute admin diawali dengan "admin/"
    ->name('admin.') // Nama rute diawali dengan "admin."
    ->group(function () {
        // 🔹 DASHBOARD ADMIN
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)
            ->name('dashboard');

        // 🔹 MANAJEMEN PENGGUNA
        Route::get('/users', \App\Livewire\Admin\Users\Index::class)
            ->name('users');

        // 🔹 MANAJEMEN BUKU
        Route::get('/books', \App\Livewire\Admin\Books\Index::class)
            ->name('books');

        // 🔹 MANAJEMEN PEMINJAMAN
        Route::get('/loans', \App\Livewire\Admin\Peminjamans\Index::class)
            ->name('loans');
    });

// =========================================================
// 📌 RUTE UNTUK HALAMAN BUKU
// =========================================================
Route::get('/books', \App\Livewire\Home\Books\Index::class)->name('books');
