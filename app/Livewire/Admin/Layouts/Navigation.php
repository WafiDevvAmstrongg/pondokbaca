<?php

namespace App\Livewire\Admin\Layouts;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navigation extends Component
{

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }
    
    public function render()
    {
        return view('livewire.admin.layouts.navigation');
    }
}
