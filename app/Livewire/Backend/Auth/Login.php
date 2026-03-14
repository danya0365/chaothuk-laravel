<?php

namespace App\Livewire\Backend\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    public function login()
    {
        $credentials = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();
            return redirect()->intended(route('backend.index'));
        }

        $this->addError('email', __('auth.failed'));
    }

    public function render()
    {
        return view('livewire.backend.auth.login')
            ->layout('layouts.backend-guest', ['title' => 'Admin Login']);
    }
}
