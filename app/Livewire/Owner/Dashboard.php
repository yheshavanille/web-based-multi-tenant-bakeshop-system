<?php

namespace App\Livewire\Owner;

use Livewire\Component;

class Dashboard extends Component
{

    public function getUserProperty()
    {
        return auth()->user()->fresh();
    }

    public function render()
    {
        return view('livewire.owner.dashboard');
    }
}
