<?php

namespace App\Livewire\Layouts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Navigation extends Component
{
    public $search = '';

    public function updatedSearch()
    {
        if (request()->routeIs('books')) {
            $this->dispatch('search-updated', search: $this->search);
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function render()
    {
        return view('livewire.layouts.navigation');
    }
}