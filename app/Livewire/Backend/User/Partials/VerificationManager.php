<?php

namespace App\Livewire\Backend\User\Partials;

use App\Models\User;
use App\Models\UserVerification;
use App\Enums\VerificationType;
use Livewire\Component;

class VerificationManager extends Component
{
    public User $user;

    // Modal State
    public $showModal = false;

    // Form Fields
    public $verification_type = '';
    public $status = 'approved'; // Default to approved since admin is adding it
    public $admin_note = '';

    protected function rules()
    {
        return [
            'verification_type' => ['required', 'string', 'in:' . implode(',', VerificationType::values())],
            'status' => ['required', 'string', 'in:pending,approved,rejected'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function openModal()
    {
        $this->reset(['verification_type', 'status', 'admin_note']);
        $this->status = 'approved';
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function saveVerification()
    {
        $this->validate();

        // Check if verification type already exists for this user
        $existing = UserVerification::where('user_id', $this->user->id)
            ->where('verification_type', $this->verification_type)
            ->first();

        if ($existing) {
            $this->addError('verification_type', 'ผู้ใช้งานรายนี้มีเอกสารยืนยันประเภทนี้อยู่ในระบบแล้ว');
            return;
        }

        $proofData = [];
        if (!empty($this->admin_note)) {
            $proofData['admin_note'] = $this->admin_note;
            $proofData['added_manually_by'] = auth()->id();
        }

        UserVerification::create([
            'user_id' => $this->user->id,
            'verification_type' => $this->verification_type,
            'status' => $this->status,
            'proof_data' => count($proofData) > 0 ? $proofData : null,
            'verified_by' => $this->status !== 'pending' ? auth()->id() : null,
            'verified_at' => $this->status !== 'pending' ? now() : null,
        ]);

        $this->user->load('verifications'); // Refresh relationships if needed
        $this->closeModal();
        session()->flash('success', 'เพิ่มข้ออมูลการยืนยันตัวตนสำเร็จ');
    }

    public function render()
    {
        $types = VerificationType::cases();
        // Load the user's verifications to display
        $verifications = UserVerification::where('user_id', $this->user->id)->latest()->get();

        return view('livewire.backend.user.partials.verification-manager', [
            'types' => $types,
            'verifications' => $verifications
        ]);
    }
}
