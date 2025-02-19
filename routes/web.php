<?php
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\Books;
use App\Livewire\Admin\Books\Index as BooksIndex;
use App\Livewire\Admin\Loans;
use App\Livewire\Admin\Peminjamans\Index as PeminjamansIndex;
use App\Livewire\Admin\Users\Index;
use App\Livewire\Home\Home;
use App\Livewire\User\Peminjamans\Index as UserPeminjamansIndex;
use App\Livewire\User\Profile\Index as ProfileIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Home::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('livewire.auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('livewire.auth.register');
    })->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::get('/profile', ProfileIndex::class)->name('profile');
    Route::get('/my-loans', UserPeminjamansIndex::class)->name('my-loans');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('/users', Index::class)->name('admin.users');
    Route::get('/books', BooksIndex::class)->name('admin.books');
    Route::get('/loans', PeminjamansIndex::class)->name('admin.loans');
});
