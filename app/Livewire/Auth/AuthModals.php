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
    public $username = '';
    public $password = '';
    public $password_confirmation = '';
    public $remember = false;

    // Validation rules
    protected function rules()
    {
        return [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|max:100',
            'username' => 'required|min:3|max:50',
            'password' => 'required|min:8',
        ];
    }

    // Specific validation rules for registration
    protected function registerRules()
    {
        return [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email|max:100',
            'username' => 'required|unique:users,username|min:3|max:50',
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

    // Livewire v3 listeners
    protected function getListeners()
    {
        return [
            'open-login-modal' => 'showLogin',
            'open-register-modal' => 'showRegister',
            'close-modals' => 'closeAllModals'
        ];
    }

    public function showLogin()
    {
        $this->resetValidation();
        $this->reset(['email', 'password', 'remember']);
        $this->showLoginModal = true;
        $this->showRegisterModal = false;
    }

    public function showRegister()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'username', 'password', 'password_confirmation']);
        $this->showRegisterModal = true;
        $this->showLoginModal = false;
    }

    public function closeAllModals()
    {
        $this->showLoginModal = false;
        $this->showRegisterModal = false;
        $this->resetValidation();
    }

    public function switchToRegister()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'username', 'password', 'password_confirmation']);
        $this->showLoginModal = false;
        $this->showRegisterModal = true;
    }

    public function switchToLogin()
    {
        $this->resetValidation();
        $this->reset(['email', 'password', 'remember']);
        $this->showRegisterModal = false;
        $this->showLoginModal = true;
    }

    // Login method
    // Login method
    public function login()
    {
        $this->validate($this->loginRules());

        if (Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            session()->regenerate();
            $this->closeAllModals();

            // Check user role and redirect accordingly
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('home');
            }
        }

        $this->addError('email', 'Kredensial yang diberikan tidak cocok dengan data kami.');
    }

    // Register method
    public function register()
    {
        $this->validate($this->registerRules());

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'role' => 'user',
            'is_active' => true
        ]);

        Auth::login($user);
        $this->closeAllModals();
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.auth.auth-modals');
    }
}
