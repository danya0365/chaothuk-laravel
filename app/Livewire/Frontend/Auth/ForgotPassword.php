<?php

namespace App\Livewire\Frontend\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';
    public ?string $successMessage = null;

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }

    public function sendResetLink(): void
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->successMessage = 'ส่งลิงก์รีเซ็ตรหัสผ่านไปยังอีเมลแล้ว กรุณาตรวจสอบอีเมลของคุณ';
            $this->email = '';
        } else {
            $this->addError('email', 'ไม่พบอีเมลนี้ในระบบ');
        }
    }

    public function render()
    {
        return view('livewire.frontend.auth.forgot-password')
            ->layout('frontend.layout', ['title' => 'ลืมรหัสผ่าน — Chaothuk']);
    }
}
