<?php

namespace App\Livewire\Backend\Notification;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\UserNotification;

class Create extends Component
{
    public $targetType = 'all'; // 'all', 'role', 'user'
    public $roleId = '';
    public $userId = '';
    public $userEmail = ''; // Used to find specific user
    
    public $title = '';
    public $message = '';
    public $type = 'system_alert';

    protected $rules = [
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'type' => 'required|string',
        'targetType' => 'required|in:all,role,user',
    ];

    public function updatedTargetType()
    {
        $this->reset(['roleId', 'userId', 'userEmail']);
        $this->resetValidation();
    }

    public function sendBroadcast()
    {
        $this->validate();

        if ($this->targetType === 'role') {
            $this->validate(['roleId' => 'required|exists:roles,id']);
            $users = User::whereHas('roles', function($q) {
                $q->where('roles.id', $this->roleId);
            })->get();
        } elseif ($this->targetType === 'user') {
            $this->validate(['userEmail' => 'required|email|exists:users,email']);
            $users = User::where('email', $this->userEmail)->get();
        } else {
            // All users
            $users = User::all();
        }

        if ($users->isEmpty()) {
            session()->flash('error', 'ไม่พบผู้รับตามเงื่อนไขที่ระบุ');
            return;
        }

        $count = 0;
        foreach ($users as $user) {
            UserNotification::create([
                'title' => $this->title,
                'details' => ['body' => $this->message],
                'notification_type' => $this->type,
                'is_read' => false,
                'author_id' => $user->id,
            ]);
            $count++;
        }

        session()->flash('success', "ส่งการแจ้งเตือนสำเร็จให้กับผู้ใช้ $count คน");
        return redirect()->route('backend.notifications.index');
    }

    public function render()
    {
        $roles = Role::orderBy('name', 'asc')->get();
        $notificationTypes = [
            'system_alert' => 'แจ้งเตือนระบบทั่วไป (System Alert)',
            'promotion' => 'โปรโมชั่น/แคมเปญ (Promotion)',
            'warning' => 'คำเตือน/ระงับการใช้งาน (Warning)',
        ];

        return view('livewire.backend.notification.create', compact('roles', 'notificationTypes'))
            ->layout('layouts.backend', ['title' => 'ส่งการแจ้งเตือน (Broadcast Notification)']);
    }
}
