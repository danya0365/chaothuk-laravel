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
    public function newTelephoneChannel()
    {
        return view('messenger.register');
    }

    public function registerTelephoneChannel()
    {
        $post = request()->all();
        $user = User::getOrCreateTelephoneUser($post['telephone']);
        $channel =  MessengerChannel::with('participants')
            ->whereHas('participants', function ($q) use ($user) {
                $q->with(['author'])->whereBelongsTo($user, 'author');
            })->first();

        if ($channel) {
            return Redirect::route('messenger.telephone-channel', ['id' => $channel->id, 'telephone' => $post['telephone']]);
        }

        $channel =  MessengerChannel::create([
            'slug' => $post['telephone'],
            'title' => $post['telephone'],
        ]);

        if (!$channel) {
            return Redirect::back()->with('status', 'profile-updated');
        }

        $channel->participants()->create([
            'user_id' => $user->id,
        ]);

        return Redirect::route('messenger.telephone-channel', ['id' => $channel->id, 'telephone' => $post['telephone']])->with('status', 'profile-updated');
    }

    /**
     * Display messenger the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getTelephoneChannel($id, $telephone)
    {
        $messengerChannel = MessengerChannel::find($id);
        $messengerParticipants = $messengerChannel->participants;
        $messengerConversations = $messengerChannel->conversations;

        return view('messenger.telephone-channel.index', compact('messengerChannel', 'messengerParticipants', 'messengerConversations', 'telephone'));
    }
}
