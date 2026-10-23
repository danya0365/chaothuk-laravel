<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreMessengerMessageRequest;
use App\Http\Resources\Api\V1\MessengerChannelCollection;
use App\Http\Resources\Api\V1\MessengerChannelResource;
use App\Http\Resources\Api\V1\MessengerConversationCollection;
use App\Http\Resources\Api\V1\MessengerConversationResource;
use App\Models\MessengerChannel;
use App\Models\MessengerConversation;
use App\Models\MessengerParticipant;
use App\Services\MessengerChannelService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Direct messages between the two sides of a booking.
 *
 * The channel id arrives from the client, and ids are small sequential integers,
 * so every read and every write resolves the caller against the channel's
 * participants before touching it. That check is the whole security model here:
 * without it any signed-in user could read and reply into any conversation by
 * guessing a number.
 */
class MessengerController extends Controller
{
    /**
     * Every room the caller is in, most recently active first.
     */
    public function index(Request $request): MessengerChannelCollection
    {
        $channelIds = MessengerParticipant::query()
            ->where('user_id', $request->user()->id)
            ->pluck('channel_id');

        $channels = MessengerChannel::query()
            ->whereIn('id', $channelIds)
            ->with(['participants.author', 'latestConversations' => fn ($query) => $query->limit(1)])
            ->orderByDesc('updated_at')
            ->paginate(min(max($request->integer('per_page', 20), 1), 50))
            ->withQueryString();

        return MessengerChannelCollection::make($channels);
    }

    /**
     * Open — creating it if needed — the room for one of the caller's bookings.
     *
     * The booking has to belong to the caller: it identifies which two people
     * get a room, so guessing an id here would put the caller in a conversation
     * they never had.
     */
    public function openForWorkBooking(Request $request, int $workBooking, MessengerChannelService $channels): MessengerChannelResource
    {
        $this->assertCallerBooking(
            \App\Models\WorkBooking::query()->where('author_id', $request->user()->id),
            \App\Models\WorkBooking::query()->whereHas('work', fn ($query) => $query->where('author_id', $request->user()->id)),
            $workBooking,
            'ไม่พบการจองงานนี้',
        );

        $channel = $channels->ensureForWorkBooking($workBooking);

        if (! $channel) {
            throw new AccessDeniedHttpException('ไม่พบการจองงานนี้');
        }

        return new MessengerChannelResource($channel);
    }

    /**
     * Open — creating it if needed — the room for one of the caller's recruit
     * applications, the mirror of the work-booking side: author_id is the
     * applicant, while the recruit's author is the employer.
     */
    public function openForRecruitBooking(Request $request, int $recruitBooking, MessengerChannelService $channels): MessengerChannelResource
    {
        $this->assertCallerBooking(
            \App\Models\RecruitBooking::query()->where('author_id', $request->user()->id),
            \App\Models\RecruitBooking::query()->whereHas('recruit', fn ($query) => $query->where('author_id', $request->user()->id)),
            $recruitBooking,
            'ไม่พบการสมัครงานนี้',
        );

        $channel = $channels->ensureForRecruitBooking($recruitBooking);

        if (! $channel) {
            throw new AccessDeniedHttpException('ไม่พบการสมัครงานนี้');
        }

        return new MessengerChannelResource($channel);
    }

    /**
     * The counterpart of a room: who the caller is talking to, and whether the
     * booking behind it is still open.
     */
    public function show(Request $request, MessengerChannel $channel): MessengerChannelResource
    {
        $this->assertParticipant($request, $channel);

        $channel->load(['participants.author', 'latestConversations' => fn ($query) => $query->limit(1)]);

        return new MessengerChannelResource($channel);
    }

    /**
     * Messages in a room, oldest first.
     *
     * `after_id` returns only what arrived since that id, which is what the app
     * polls: the composite index on (channel_id, id) makes it a range scan
     * instead of re-reading the room each time.
     */
    public function messages(Request $request, MessengerChannel $channel): MessengerConversationCollection
    {
        $this->assertParticipant($request, $channel);

        $query = MessengerConversation::query()
            ->with('author')
            ->where('channel_id', $channel->id);

        if ($afterId = $request->integer('after_id')) {
            $query->where('id', '>', $afterId);
        }

        $messages = $query
            ->orderBy('id')
            ->limit(min(max($request->integer('per_page', 50), 1), 200))
            ->get();

        return MessengerConversationCollection::make($messages);
    }

    /**
     * Post a message into a room the caller belongs to.
     *
     * Bumping the channel's updated_at is what reorders the room list, so a new
     * conversation surfaces at the top without a separate read-state column.
     */
    public function storeMessage(
        StoreMessengerMessageRequest $request,
        MessengerChannel $channel,
    ): MessengerConversationResource {
        $this->assertParticipant($request, $channel);

        $post = $request->validated();

        $message = MessengerConversation::create([
            'content' => $post['content'],
            'type' => $post['type'] ?? 'text',
            'local_code_id' => $post['local_code_id'] ?? 'api-'.auth()->id().'-'.now()->timestamp,
            'user_id' => $request->user()->id,
            'channel_id' => $channel->id,
        ]);

        $channel->touch();

        return new MessengerConversationResource($message->load('author'));
    }

    /**
     * Mark the caller's messages in a room as seen.
     *
     * Scoped to user_id so a caller can never clear the other side's receipts.
     */
    public function markSeen(Request $request, MessengerChannel $channel): MessengerConversationCollection
    {
        $this->assertParticipant($request, $channel);

        $messages = MessengerConversation::query()
            ->with('author')
            ->where('channel_id', $channel->id)
            ->where('user_id', '!=', $request->user()->id)
            ->whereNull('seen_at')
            ->orderBy('id')
            ->get();

        if ($messages->isNotEmpty()) {
            MessengerConversation::query()
                ->whereIn('id', $messages->pluck('id'))
                ->update(['seen_at' => now()]);
        }

        MessengerParticipant::query()
            ->where('channel_id', $channel->id)
            ->where('user_id', $request->user()->id)
            ->update(['last_seen_at' => now()]);

        return MessengerConversationCollection::make($messages);
    }

    /**
     * The caller must be one of the two participants.
     */
    private function assertParticipant(Request $request, MessengerChannel $channel): void
    {
        $isParticipant = MessengerParticipant::query()
            ->where('channel_id', $channel->id)
            ->where('user_id', $request->user()->id)
            ->exists();

        if (! $isParticipant) {
            throw new AccessDeniedHttpException('คุณไม่มีสิทธิ์เข้าถึงการสนทนานี้');
        }
    }

    /**
     * The booking must involve the caller on at least one side.
     */
    private function assertCallerBooking($ownQuery, $counterpartyQuery, int $bookingId, string $message): void
    {
        $exists = $ownQuery->whereKey($bookingId)->exists()
            || $counterpartyQuery->whereKey($bookingId)->exists();

        if (! $exists) {
            throw new AccessDeniedHttpException($message);
        }
    }
}
