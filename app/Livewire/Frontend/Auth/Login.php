<?php

namespace App\Livewire\Frontend\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function login(): void
    {
        $this->validate();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง');
            return;
        }

        session()->regenerate();
        $this->redirect(route('frontend.home'), navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.auth.login')
            ->layout('frontend.layout', ['title' => 'เข้าสู่ระบบ — Chaothuk']);
    }
}
