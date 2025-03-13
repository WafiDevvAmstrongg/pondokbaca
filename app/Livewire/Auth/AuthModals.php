<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AuthModals extends Component
{
    public $showLoginModal = false;
    public $showRegisterModal = false;

    // Form properties
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $remember = false;
    
    // Custom error message
    public $loginError = '';

    // Validation rules
    protected function rules()
    {
        return [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|max:100',
            'password' => 'required|min:8',
        ];
    }

    // Specific validation rules for registration
    protected function registerRules()
    {
        return [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|min:8|confirmed',
        ];
    }

    // Login form validation rules
    protected function loginRules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    // Custom validation messages
    protected function messages()
    {
        return [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
        ];
    }

    // Livewire v3 listeners
    protected function getListeners()
    {
        return [
            'open-login-modal' => 'openLoginModal',
            'open-register-modal' => 'showRegister',
            'close-modals' => 'closeAllModals'
        ];
    }

    protected $listeners = ['open-login-modal' => 'openLoginModal'];

    public function openLoginModal()
    {
        $this->resetValidation();
        $this->reset(['email', 'password', 'remember', 'loginError']);
        $this->showLoginModal = true;
        $this->showRegisterModal = false;
    }

    public function showRegister()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'loginError']);
        $this->showRegisterModal = true;
        $this->showLoginModal = false;
    }

    public function closeAllModals()
    {
        $this->showLoginModal = false;
        $this->showRegisterModal = false;
        $this->resetValidation();
        $this->reset(['loginError']);
    }

    public function switchToRegister()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'loginError']);
        $this->showLoginModal = false;
        $this->showRegisterModal = true;
    }

    public function switchToLogin()
    {
        $this->resetValidation();
        $this->reset(['email', 'password', 'remember', 'loginError']);
        $this->showRegisterModal = false;
        $this->showLoginModal = true;
    }

    // Login method
    public function login()
    {
        // Reset any previous login error
        $this->loginError = '';
        
        // Validate using the loginRules method
        $this->validate($this->loginRules(), $this->messages());

        // Check if the user exists first
        $user = User::where('email', $this->email)->first();
        
        if (!$user) {
            $this->loginError = 'Email tidak terdaftar';
            return;
        }

        // Check if the credentials are valid
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            $this->closeAllModals();

            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Selamat datang kembali, ' . Auth::user()->name,
                'icon' => 'success'
            ]);

            if (session()->has('checkout_book_id')) {
                $this->dispatch('showDetailModal', ['bookId' => session('checkout_book_id')]);
            }

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        } else {
            // Password is incorrect
            $this->loginError = 'Password yang Anda masukkan salah';
            
            // Clear the password field for security
            $this->password = '';
        }
    }

    // Register method
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

            Auth::login($user);
            $this->closeAllModals();

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

    public function render()
    {
        return view('livewire.auth.auth-modals');
    }
}