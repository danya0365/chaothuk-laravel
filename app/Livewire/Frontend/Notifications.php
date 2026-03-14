<?php

namespace App\Livewire\Frontend;

use App\Models\Notification;
use App\Models\UserNotification;
use Livewire\Component;

class Notifications extends Component
{
    public ?array $notifications = null;

    public function mount(): void
    {
        $this->load();
    }

    public function load(): void
    {
        $this->notifications = UserNotification::with(['notificationable'])
            ->where('author_id', auth()->id())
            ->latest()
            ->limit(30)
            ->get()
            ->toArray();
    }

    public function markRead(int $id): void
    {
        UserNotification::where('id', $id)
            ->where('author_id', auth()->id())
            ->update(['is_read' => true]);
        $this->load();
    }

    public function markAllRead(): void
    {
        UserNotification::where('author_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        $this->load();
    }

    public function render()
    {
        return view('livewire.frontend.notifications')
            ->layout('frontend.layout', ['title' => 'แจ้งเตือน — Chaothuk']);
    }
}
