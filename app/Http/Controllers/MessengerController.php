<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MessengerChannel;
use App\Models\MessengerParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Class MessengerChannelController
 * @package App\Http\Controllers
 */
class MessengerController extends Controller
{
    public function newMobilePhoneChannel()
    {
        return view('messenger.register');
    }

    public function registerMobilePhoneChannel()
    {
        $post = request()->all();
        $user = User::getOrCreateMobilePhoneUser($post['mobilePhone']);
        $channel =  MessengerChannel::with('participants')
            ->whereHas('participants', function ($q) use ($user) {
                $q->with(['author'])->whereBelongsTo($user, 'author');
            })->first();

        if ($channel) {
            return Redirect::route('messenger.mobilephone-channel', ['id' => $channel->id, 'mobilePhone' => $post['mobilePhone']]);
        }

        $channel =  MessengerChannel::create([
            'slug' => $post['mobilePhone'],
            'title' => $post['mobilePhone'],
        ]);

        if (!$channel) {
            return Redirect::back()->with('status', 'profile-updated');
        }

        $channel->participants()->create([
            'user_id' => $user->id,
        ]);

        return Redirect::route('messenger.mobilephone-channel', ['id' => $channel->id, 'mobilePhone' => $post['mobilePhone']])->with('status', 'profile-updated');
    }

    /**
     * Display messenger the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getMobilePhoneChannel($id, $mobilePhone)
    {
        $messengerChannel = MessengerChannel::find($id);
        $messengerParticipants = $messengerChannel->participants;
        $messengerConversations = $messengerChannel->conversations;

        return view('messenger.mobilephone-channel.index', compact('messengerChannel', 'messengerParticipants', 'messengerConversations', 'mobilePhone'));
    }
}
