<?php

// 📌 Namespace untuk menentukan lokasi class ini dalam struktur Livewire Auth
namespace App\Livewire\Auth;

// 📌 Mengimpor model User untuk autentikasi dan registrasi pengguna
use App\Models\User;

// 📌 Mengimpor Auth untuk menangani login/logout
use Illuminate\Support\Facades\Auth;

// 📌 Mengimpor Hash untuk mengenkripsi password
use Illuminate\Support\Facades\Hash;

// 📌 Mengimpor Livewire Component untuk membuat komponen modal login & registrasi
use Livewire\Component;

class AuthModals extends Component
{
    // 📌 Variabel untuk mengontrol tampilan modal
    public $showLoginModal = false; // Modal login
    public $showRegisterModal = false; // Modal registrasi

    // 📌 Variabel untuk input form login & registrasi
    public $name = ''; // Nama pengguna (hanya untuk registrasi)
    public $email = ''; // Email pengguna
    public $password = ''; // Password pengguna
    public $password_confirmation = ''; // Konfirmasi password (hanya untuk registrasi)
    public $remember = false; // Status "ingat saya" saat login

    // 📌 Variabel untuk menampilkan pesan error login
    public $loginError = '';

    /**
     * 📌 ATURAN VALIDASI FORM REGISTRASI & LOGIN
     */
    protected function rules()
    {
        return [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|max:100',
            'password' => 'required|min:8',
        ];
    }

    protected function registerRules()
    {
        return [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|min:8|confirmed',
        ];
    }

    protected function loginRules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    /**
     * 📌 CUSTOM PESAN VALIDASI
     */
    protected function messages()
    {
        return [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
        ];
    }

    /**
     * 📌 LISTENER UNTUK MEMBUKA DAN MENUTUP MODAL
     */
    protected function getListeners()
    {
        return [
            'open-login-modal' => 'openLoginModal',
            'open-register-modal' => 'showRegister',
            'close-modals' => 'closeAllModals'
        ];
    }

    protected $listeners = ['open-login-modal' => 'openLoginModal'];

    /**
     * 📌 FUNGSI UNTUK MEMBUKA MODAL LOGIN
     */
    public function openLoginModal()
    {
        $this->resetValidation();
        $this->reset(['email', 'password', 'remember', 'loginError']);
        $this->showLoginModal = true;
        $this->showRegisterModal = false;
    }

    /**
     * 📌 FUNGSI UNTUK MEMBUKA MODAL REGISTRASI
     */
    public function showRegister()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'loginError']);
        $this->showRegisterModal = true;
        $this->showLoginModal = false;
    }

    /**
     * 📌 FUNGSI UNTUK MENUTUP SEMUA MODAL
     */
    public function closeAllModals()
    {
        $this->showLoginModal = false;
        $this->showRegisterModal = false;
        $this->resetValidation();
        $this->reset(['loginError']);
    }

    /**
     * 📌 FUNGSI UNTUK BERPINDAH DARI LOGIN KE REGISTRASI
     */
    public function switchToRegister()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'loginError']);
        $this->showLoginModal = false;
        $this->showRegisterModal = true;
    }

    /**
     * 📌 FUNGSI UNTUK BERPINDAH DARI REGISTRASI KE LOGIN
     */
    public function switchToLogin()
    {
        $this->resetValidation();
        $this->reset(['email', 'password', 'remember', 'loginError']);
        $this->showRegisterModal = false;
        $this->showLoginModal = true;
    }

    /**
     * 📌 FUNGSI LOGIN
     * - Memeriksa apakah email & password valid
     * - Jika sukses, arahkan pengguna ke dashboard / home
     * - Jika gagal, tampilkan pesan error
     */
    public function login()
    {
        $this->loginError = '';

        // Validasi input login
        $this->validate($this->loginRules(), $this->messages());

        // Cek apakah email terdaftar
        $user = User::where('email', $this->email)->first();
        if (!$user) {
            $this->loginError = 'Email tidak terdaftar';
            return;
        }

        // Cek apakah password benar
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            $this->closeAllModals();

            // Tampilkan notifikasi sukses
            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Selamat datang kembali, ' . Auth::user()->name,
                'icon' => 'success'
            ]);

            // Jika ada sesi checkout buku, tampilkan detail buku
            if (session()->has('checkout_book_id')) {
                $this->dispatch('showDetailModal', ['bookId' => session('checkout_book_id')]);
            }

            // Redirect berdasarkan peran pengguna
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        } else {
            $this->loginError = 'Password yang Anda masukkan salah';
            $this->password = ''; // Hapus password untuk keamanan
        }
    }

    /**
     * 📌 FUNGSI REGISTRASI
     * - Mendaftarkan pengguna baru ke database
     * - Menggunakan hashing password
     * - Langsung login setelah berhasil mendaftar
     */
    public function register()
    {
        $this->validate($this->registerRules(), $this->messages());

        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => 'user',
                'is_active' => true
            ]);

            // Login otomatis setelah registrasi
            Auth::login($user);
            $this->closeAllModals();

            // Notifikasi sukses
            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Selamat datang di PondokBaca, ' . $user->name,
                'icon' => 'success'
            ]);

            return redirect()->route('home');
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat mendaftarkan akun.',
                'icon' => 'error'
            ]);
        }
    }

    /**
     * 📌 MENAMPILKAN VIEW AUTH MODALS
     */
    public function render()
    {
        return view('livewire.auth.auth-modals');
    }
}
