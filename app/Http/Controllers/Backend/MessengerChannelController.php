<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MessengerChannel;
use App\Models\MessengerParticipant;
use Illuminate\Http\Request;

/**
 * Class MessengerChannelController
 * @package App\Http\Controllers
 */
class MessengerChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messengerChannels = MessengerChannel::orderBy('updated_at', 'desc')->paginate()->withQueryString();

        return view('backend.messenger-channel.index', compact('messengerChannels'))
            ->with('i', (request()->input('page', 1) - 1) * $messengerChannels->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $messengerChannel = new MessengerChannel();
        return view('backend.messenger-channel.create', compact('messengerChannel'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(MessengerChannel::$rules);

        $messengerChannel = MessengerChannel::create($request->all());

        return redirect()->route('backend.messenger-channels.index')
            ->with('success', 'MessengerChannel created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $messengerChannel = MessengerChannel::find($id);

        return view('backend.messenger-channel.show', compact('messengerChannel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $messengerChannel = MessengerChannel::find($id);

        return view('backend.messenger-channel.edit', compact('messengerChannel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  MessengerChannel $messengerChannel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MessengerChannel $messengerChannel)
    {
        request()->validate(MessengerChannel::$rules);

        $messengerChannel->update($request->all());

        return redirect()->route('backend.messenger-channels.index')
            ->with('success', 'MessengerChannel updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $messengerChannel = MessengerChannel::find($id)->delete();

        return redirect()->route('backend.messenger-channels.index')
            ->with('success', 'MessengerChannel deleted successfully');
    }
}
