<?php

namespace App\Livewire\Backend\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend-guest')]
#[Title('Access Denied - Admin')]
class Unauthorize extends Component
{
    public function render()
    {
        return view('livewire.backend.auth.unauthorize');
    }
}
