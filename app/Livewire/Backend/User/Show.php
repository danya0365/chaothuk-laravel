<?php

namespace App\Livewire\Backend\User;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('รายละเอียดผู้ใช้งาน - Admin')]
class Show extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user->load([
            'roles', 
            'permissions', 
            'userPoints',
            'transactions',
            'works',
            'posts',
            'notifications',
            'reputation',
            'badges',
            'reputationReviews',
            'givenReviews',
            'reports'
        ]);
    }

    public function render()
    {
        return view('livewire.backend.user.show');
    }
}
