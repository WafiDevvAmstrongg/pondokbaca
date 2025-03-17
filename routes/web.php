<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Front/Public Routes
Route::get('/', \App\Livewire\Home\Home::class)->name('home');

// Guest Routes
Route::middleware('guest')->group(function () {
});

// User Routes
Route::middleware('auth')->group(function () {
    // Auth
    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::get('/admin/dashboard', \App\Livewire\Admin\Dashboard::class)
    ->middleware('auth')
    ->name('admin.dashboard');


    // User Profile
    Route::get('/profile', \App\Livewire\User\Profile\Index::class)
        ->name('profile');

    // User Loans
    Route::get('/my-loans', \App\Livewire\User\Peminjamans\Index::class)
        ->name('my-loans');

    Route::get('/checkout/{token}', \App\Livewire\User\Checkout::class)
        ->name('user.checkout');

    Route::get('/peminjaman', App\Livewire\User\Peminjamans\Index::class)->name('user.peminjaman');

    Route::get('/pembayaran', App\Livewire\User\Pembayaran\Index::class)->name('user.pembayaran');
});
   
// Admin Routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)
            ->name('dashboard');

        // Admin Users Management
        Route::get('/users', \App\Livewire\Admin\Users\Index::class)
            ->name('users');

        // Admin Books Management
        Route::get('/books', \App\Livewire\Admin\Books\Index::class)
            ->name('books');

        // Admin Loans Management
        Route::get('/loans', \App\Livewire\Admin\Peminjamans\Index::class)
            ->name('loans');
    });

Route::get('/books', \App\Livewire\Home\Books\Index::class)->name('books');
