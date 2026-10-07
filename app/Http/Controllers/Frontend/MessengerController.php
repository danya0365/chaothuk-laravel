<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\MessengerConversationType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMobilePhoneMessengerChannelRequest;
use App\Http\Requests\CreateMobilePhoneMessengerConversationRequest;
use App\Http\Resources\MessengerConversationCollection;
use App\Http\Resources\MessengerConversationResource;
use App\Models\MessengerChannel;
use App\Models\MessengerConversation;
use App\Models\User;
use Carbon\Carbon;

/**
 * Messenger endpoints for the browser session (guest by mobile phone).
 *
 * Moved out of the Api namespace when the mobile API was split into its own
 * versioned surface. Only the guest mobile-phone flow is kept here — the logged-in
 * channel endpoints now belong to the mobile API.
 */
class MessengerController extends Controller
{
    public function newMobilePhoneChannel(CreateMobilePhoneMessengerChannelRequest $request)
    {
        $post = $request->validated();
        $user = User::getOrCreateMobilePhoneUser($post['mobile_phone']);
        $channel = MessengerChannel::with('participants')
            ->whereHas('participants', function ($q) use ($user) {
                $q->with(['author'])->whereBelongsTo($user, 'author');
            })->first();

        if ($channel) {
            return response()->json([
                'status' => true,
                'data' => $channel,
            ], 200);
        }

        $channel = MessengerChannel::create([
            'slug' => $post['mobile_phone'],
            'title' => $post['mobile_phone'],
        ]);

        if (! $channel) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }

        $channel->participants()->create([
            'user_id' => $user->id,
        ]);

        return response()->json([
            'status' => true,
            'data' => $channel,
        ], 200);
    }

    public function getMobilePhoneChannelConversations($channelId, $mobilePhone)
    {
        $channel = MessengerChannel::find($channelId);
        if (! $channel) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }

        if (! $this->isMobilePhoneParticipant($channel, $mobilePhone)) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }

        $data = $channel->conversations()
            ->with('author')
            ->orderBy('id', 'desc')
            ->limitOffset(request()->all())->get();

        return response()->json([
            'status' => true,
            'data' => new MessengerConversationCollection($data),
        ], 200);
    }

    public function storeMobilePhoneChannelConversations(CreateMobilePhoneMessengerConversationRequest $request, $channelId, $mobilePhone)
    {
        $post = $request->validated();
        $user = User::getOrCreateMobilePhoneUser($mobilePhone);
        $channel = MessengerChannel::find($channelId);
        if (! $channel) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }

        if (! $this->isMobilePhoneParticipant($channel, $mobilePhone)) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }

        $data = $channel->conversations()
            ->create([
                'content' => $post['content'],
                'type' => $post['type'] ?? MessengerConversationType::TEXT->value,
                'local_code_id' => $post['local_code_id'],
                'user_id' => $user->id,
            ]);

        $channel->touch();

        return response()->json([
            'status' => true,
            'data' => new MessengerConversationResource($data),
        ], 200);
    }

    public function updateSeenAtInConversations($id, $conversationId)
    {
        $channel = MessengerChannel::find($id);
        if (! $channel) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }

        $channel->touch();

        $messengerConversation = MessengerConversation::find($conversationId);
        if (! $messengerConversation) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }

        $messengerConversation->seen_at = Carbon::now();
        $messengerConversation->save();

        return response()->json([
            'status' => true,
            'data' => new MessengerConversationResource($messengerConversation),
        ], 200);
    }

    /**
     * The guest conversation is only readable by that mobile phone's own participant.
     */
    private function isMobilePhoneParticipant(MessengerChannel $channel, string $mobilePhone): bool
    {
        return $channel->participants
            ->contains(fn ($participant) => $participant->is_customer && $participant->author?->name === $mobilePhone);
    }
}
