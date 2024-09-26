<?php

namespace App\Http\Controllers\Api;

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
use Illuminate\Http\Request;

/**
 * Class MessengerController
 * @package App\Http\Controllers
 */
class MessengerController extends Controller
{
    public function getChannelConversations($channelId)
    {
        $channel  =  MessengerChannel::find($channelId);
        if (!$channel) {
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

    public function storeChannelConversations($channelId)
    {
        $post = request()->all();
        $user = request()->user();
        $channel =  MessengerChannel::find($channelId);
        if (!$channel) {
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
                'user_id' => $user->id
            ]);

        $channel->touch();

        return response()->json([
            'status' => true,
            'data' => new MessengerConversationResource($data),
        ], 200);
    }

    public function newMobilePhoneChannel(CreateMobilePhoneMessengerChannelRequest $request)
    {
        $post = $request->validated();
        $user = User::getOrCreateMobilePhoneUser($post['mobile_phone']);
        $channel =  MessengerChannel::with('participants')
            ->whereHas('participants', function ($q) use ($user) {
                $q->with(['author'])->whereBelongsTo($user, 'author');
            })->first();

        if ($channel) {
            return response()->json([
                'status' => true,
                'data' => $channel,
            ], 200);
        }

        $channel =  MessengerChannel::create([
            'slug' => $post['mobile_phone'],
            'title' => $post['mobile_phone'],
        ]);

        if (!$channel) {
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

    public function getMyChannel(Request $request)
    {
        $user = $request->user();
        $channel =  MessengerChannel::with('participants')
            ->whereHas('participants', function ($q) use ($user) {
                $q->with(['author'])->whereBelongsTo($user, 'author');
            })->first();

        if ($channel) {
            return response()->json([
                'status' => true,
                'data' => $channel,
            ], 200);
        }

        $channel =  MessengerChannel::create([
            'slug' => $user['email'],
            'title' => $user['email'],
        ]);

        if (!$channel) {
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
        $channel  =  MessengerChannel::find($channelId);
        if (!$channel) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }
        $participants = $channel->participants;
        $customerParticipant = null;
        foreach ($participants as $participant) {
            if ($participant->is_customer && $participant->author->name == $mobilePhone) {
                $customerParticipant = $participant;
                break;
            }
        }

        if (!$customerParticipant) {
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


    public function getLastChannelConversations(Request $request, $channelId)
    {
        $user = $request->user();
        $channel  =  MessengerChannel::find($channelId);
        if (!$channel) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }
        $participants = $channel->participants;
        $userParticipant = null;
        foreach ($participants as $participant) {
            if ($participant->author->email == $user->email) {
                $userParticipant = $participant;
                break;
            }
        }

        if (!$userParticipant) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }

        $data = $channel->conversations()
            ->with('author')
            ->orderBy('id', 'desc')
            ->first();

        return response()->json([
            'status' => $data ? true : false,
            'data' => $data ? new MessengerConversationResource($data) : null,
        ], 200);
    }

    public function getLastMobilePhoneChannelConversations($channelId, $mobilePhone)
    {
        $channel  =  MessengerChannel::find($channelId);
        if (!$channel) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }
        $participants = $channel->participants;
        $customerParticipant = null;
        foreach ($participants as $participant) {
            if ($participant->is_customer && $participant->author->name == $mobilePhone) {
                $customerParticipant = $participant;
                break;
            }
        }

        if (!$customerParticipant) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }

        $data = $channel->conversations()
            ->with('author')
            ->orderBy('id', 'desc')
            ->first();

        return response()->json([
            'status' => $data ? true : false,
            'data' => $data ? new MessengerConversationResource($data) : null,
        ], 200);
    }

    public function storeMobilePhoneChannelConversations(CreateMobilePhoneMessengerConversationRequest $request, $channelId, $mobilePhone)
    {
        $post = $request->validated();
        $user = User::getOrCreateMobilePhoneUser($mobilePhone);
        $channel =  MessengerChannel::find($channelId);
        if (!$channel) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }
        $participants = $channel->participants;
        $customerParticipant = null;
        foreach ($participants as $participant) {
            if ($participant->is_customer && $participant->author->name == $mobilePhone) {
                $customerParticipant = $participant;
                break;
            }
        }

        if (!$customerParticipant) {
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
                'user_id' => $user->id
            ]);

        $channel->touch();

        return response()->json([
            'status' => true,
            'data' => new MessengerConversationResource($data),
        ], 200);
    }

    public function updateSeenAtInConversations($id, $conversationId)
    {
        $channel =  MessengerChannel::find($id);
        if (!$channel) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 404);
        }
        $channel->touch();

        $messengerConversation  =  MessengerConversation::find($conversationId);
        if (!$messengerConversation) {
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
}
