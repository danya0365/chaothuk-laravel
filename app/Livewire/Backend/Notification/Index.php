<?php

namespace App\Livewire\Backend\Notification;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserNotification;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = UserNotification::query()->with(['author', 'notificationable']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('details', 'like', '%' . $this->search . '%')
                  ->orWhereHas('author', function($qAuthor) {
                      $qAuthor->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->typeFilter) {
            $query->where('notification_type', $this->typeFilter);
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get distinct types for the filter dropdown
        $types = UserNotification::select('notification_type')->distinct()->pluck('notification_type');

        return view('livewire.backend.notification.index', compact('notifications', 'types'))
            ->layout('layouts.backend', ['title' => 'ประวัติการแจ้งเตือน (Notifications)']);
    }
}
