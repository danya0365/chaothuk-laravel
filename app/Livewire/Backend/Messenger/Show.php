<?php

namespace App\Livewire\Backend\Messenger;

use App\Models\MessengerChannel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.backend')]
#[Title('ประวัติการสนทนา - Admin')]
class Show extends Component
{
    use WithPagination;

    public MessengerChannel $channel;

    public function mount(MessengerChannel $channel)
    {
        $this->channel = $channel->loadMissing('participants.author');
    }

    public function render()
    {
        // Load conversations with authors chronologically (latest first for pagination, then reversed in view, or earliest first?)
        // Let's load them oldest first so it reads top-to-bottom like a normal chat history.
        $conversations = $this->channel->conversations()
            ->with('author')
            ->oldest()
            ->paginate(50);

        return view('livewire.backend.messenger.show', [
            'conversations' => $conversations
        ]);
    }
}
