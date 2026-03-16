<?php

namespace App\Livewire\Backend\Messenger;

use App\Models\MessengerChannel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.backend')]
#[Title('จัดการแชททั้งหมด - Admin')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = MessengerChannel::withCount('participants')
            ->with(['latestConversations' => function ($query) {
                // Ensure we get the latest conversation loaded
                $query->with('author');
            }]);

        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%');
        }

        $channels = $query->latest('updated_at')->paginate(10);

        return view('livewire.backend.messenger.index', compact('channels'));
    }
}
