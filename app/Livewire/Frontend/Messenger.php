<?php

namespace App\Livewire\Frontend;

use App\Models\MessengerChannel;
use App\Models\MessengerConversation;
use App\Models\MessengerParticipant;
use Livewire\Component;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Messenger extends Component
{
    public ?array $channels = null;

    public ?int $activeChannelId = null;

    public ?array $conversations = null;

    public ?array $activeChannel = null;

    public string $newMessage = '';

    public function mount(): void
    {
        $this->loadChannels();
    }

    public function loadChannels(): void
    {
        $uid = auth()->id();
        $channelIds = MessengerParticipant::where('user_id', $uid)->pluck('channel_id');

        $this->channels = MessengerChannel::whereIn('id', $channelIds)
            ->with(['participants.author'])
            ->latest('updated_at')
            ->get()
            ->map(function ($ch) use ($uid) {
                $otherUser = $ch->participants
                    ->where('user_id', '!=', $uid)
                    ->first()?->author;

                $lastMsg = MessengerConversation::where('channel_id', $ch->id)
                    ->latest()->first();

                return [
                    'id' => $ch->id,
                    'title' => $ch->is_direct
                        ? ($otherUser?->name ?? $ch->title)
                        : $ch->title,
                    'avatar' => $otherUser?->profile_image,
                    'last_msg' => $lastMsg?->content ?? '',
                    'last_time' => $lastMsg?->created_at?->diffForHumans() ?? '',
                    'is_direct' => $ch->is_direct,
                ];
            })
            ->toArray();

        // Auto-select first channel
        if (! $this->activeChannelId && count($this->channels) > 0) {
            $this->openChannel($this->channels[0]['id']);
        }
    }

    /**
     * Open a channel the signed-in user actually belongs to.
     *
     * The channel id reaches this method straight from the wire, so without this
     * check any logged-in user could read — and then reply into — someone else's
     * conversation by guessing a small integer.
     */
    public function openChannel(int $channelId): void
    {
        $uid = auth()->id();

        $isParticipant = MessengerParticipant::query()
            ->where('channel_id', $channelId)
            ->where('user_id', $uid)
            ->exists();

        if (! $isParticipant) {
            throw new AccessDeniedHttpException('คุณไม่มีสิทธิ์เข้าถึงการสนทนานี้');
        }

        $this->activeChannelId = $channelId;
        $this->activeChannel = collect($this->channels)->firstWhere('id', $channelId);

        $this->conversations = MessengerConversation::with(['author'])
            ->where('channel_id', $channelId)
            ->orderBy('created_at', 'asc')
            ->limit(100)
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'content' => $c->content,
                'type' => $c->type,
                'is_mine' => $c->user_id === auth()->id(),
                'user' => $c->author?->name ?? '-',
                'avatar' => $c->author?->profile_image,
                'time' => $c->created_at?->format('H:i'),
            ])
            ->toArray();
    }

    public function sendMessage(): void
    {
        if (trim($this->newMessage) === '' || ! $this->activeChannelId) {
            return;
        }

        // Guard again at the write path: $activeChannelId is client-controlled state,
        // so re-checking here is what actually stops a forged send.
        $isParticipant = MessengerParticipant::query()
            ->where('channel_id', $this->activeChannelId)
            ->where('user_id', auth()->id())
            ->exists();

        if (! $isParticipant) {
            throw new AccessDeniedHttpException('คุณไม่มีสิทธิ์ส่งข้อความในการสนทนานี้');
        }

        MessengerConversation::create([
            'type' => 'text',
            'content' => $this->newMessage,
            'local_code_id' => 'web-'.auth()->id().'-'.now()->timestamp,
            'user_id' => auth()->id(),
            'channel_id' => $this->activeChannelId,
        ]);

        $this->newMessage = '';
        $this->openChannel($this->activeChannelId);
    }

    public function render()
    {
        return view('livewire.frontend.messenger')
            ->layout('frontend.layout', ['title' => 'แชท — Chaothuk']);
    }
}
