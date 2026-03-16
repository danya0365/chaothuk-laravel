<?php

namespace App\Livewire\Backend\Verification;

use App\Models\UserVerification;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('ตรวจสอบการยืนยันตัวตน - Admin')]
class Show extends Component
{
    public UserVerification $verification;

    // Reject Reason
    public $showRejectModal = false;
    public $rejectReason = '';

    public function mount(UserVerification $verification)
    {
        $this->verification = $verification->load('user');
    }

    public function approve()
    {
        $this->verification->update([
            'status' => 'approved',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Optional: Update user's verification status or grant a badge here
        // e.g., $this->verification->user->update(['is_verified' => true]);

        session()->flash('success', 'อนุมัติการยืนยันตัวตนเรียบร้อยแล้ว');
        return $this->redirectRoute('backend.verifications.index', navigate: true);
    }

    public function openRejectModal()
    {
        $this->rejectReason = '';
        $this->showRejectModal = true;
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
    }

    public function reject()
    {
        $this->validate([
            'rejectReason' => 'required|string|min:5|max:500',
        ]);

        $this->verification->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Save the reason into proof_data or a new column if exists. 
        // For now, we append it to proof_data.
        $proofData = is_array($this->verification->proof_data) ? $this->verification->proof_data : [];
        $proofData['reject_reason'] = $this->rejectReason;
        $this->verification->update(['proof_data' => $proofData]);

        session()->flash('success', 'ปฏิเสธการยืนยันตัวตนและบันทึกเหตุผลแล้ว');
        return $this->redirectRoute('backend.verifications.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.backend.verification.show');
    }
}
