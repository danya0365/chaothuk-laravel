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
        $user = User::getOrCreateMobilePhoneUser($post['mobile_phone']);
        $channel =  MessengerChannel::with('participants')
            ->whereHas('participants', function ($q) use ($user) {
                $q->with(['author'])->whereBelongsTo($user, 'author');
            })->first();

        if ($channel) {
            return Redirect::route('messenger.mobile-phone-channel', ['id' => $channel->id, 'mobilePhone' => $post['mobile_phone']]);
        }

        $channel =  MessengerChannel::create([
            'slug' => $post['mobile_phone'],
            'title' => $post['mobile_phone'],
        ]);

        if (!$channel) {
            return Redirect::back()->with('status', 'Error for create channel');
        }

        $channel->participants()->create([
            'user_id' => $user->id,
        ]);

        return Redirect::route('messenger.mobile-phone-channel', ['id' => $channel->id, 'mobilePhone' => $post['mobile_phone']]);
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

        return view('messenger.mobile-phone-channel.index', compact('messengerChannel', 'messengerParticipants', 'messengerConversations', 'mobilePhone'));
    }
}
