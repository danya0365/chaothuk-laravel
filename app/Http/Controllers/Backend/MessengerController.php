<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MessengerChannel;

/**
 * Class MessengerChannelController
 * @package App\Http\Controllers
 */
class MessengerController extends Controller
{
    /**
     * Display messenger the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getChannel($id)
    {
        $messengerChannel = MessengerChannel::find($id);
        $messengerParticipants = $messengerChannel->participants;
        $messengerConversations = $messengerChannel->conversations;

        return view('backend.messenger.index', compact('messengerChannel', 'messengerParticipants', 'messengerConversations'));
    }
}
