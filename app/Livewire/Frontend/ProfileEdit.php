<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class ProfileEdit extends Component
{
    public string $firstName = '';
    public string $lastName = '';
    public string $name = '';
    public string $mobilePhone = '';
    public string $location = '';
    public string $biography = '';
    public string $profileImage = '';

    public function mount(): void
    {
        $u = auth()->user();
        $this->firstName    = $u->first_name ?? '';
        $this->lastName     = $u->last_name ?? '';
        $this->name         = $u->name ?? '';
        $this->mobilePhone  = $u->mobile_phone ?? '';
        $this->location     = $u->location ?? '';
        $this->biography    = $u->biography ?? '';
        $this->profileImage = $u->profile_image ?? '';
    }

    public function removeAvatar(): void
    {
        $this->profileImage = '';
    }

    public function save(): void
    {
        $this->validate([
            'firstName'   => 'required|min:1|max:100',
            'lastName'    => 'required|min:1|max:100',
            'name'        => 'required|min:2|max:100',
            'mobilePhone' => 'nullable|min:9|max:15',
            'location'    => 'nullable|max:255',
            'biography'   => 'nullable|max:1000',
        ], [
            'firstName.required'  => 'กรุณากรอกชื่อจริง',
            'lastName.required'   => 'กรุณากรอกนามสกุล',
            'name.required'       => 'กรุณากรอกชื่อที่แสดง',
            'mobilePhone.min'     => 'เบอร์โทรต้องมีอย่างน้อย 9 หลัก',
        ]);

        auth()->user()->update([
            'first_name'    => $this->firstName,
            'last_name'     => $this->lastName,
            'name'          => $this->name,
            'mobile_phone'  => $this->mobilePhone ?: null,
            'location'      => $this->location ?: null,
            'biography'     => $this->biography ?: null,
            'profile_image' => $this->profileImage ?: null,
        ]);

        $this->redirect(route('frontend.profile'), navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.profile-edit')
            ->layout('frontend.layout', ['title' => 'แก้ไขโปรไฟล์ — Chaothuk']);
    }
}
