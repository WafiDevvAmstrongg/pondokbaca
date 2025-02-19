<?php

namespace App\Livewire\Home;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.home')->layout('components.layouts.root', [
            'title' => 'Home - PondokBaca'
        ]);
    }
}
