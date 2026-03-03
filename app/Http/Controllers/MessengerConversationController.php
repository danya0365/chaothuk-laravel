<?php

namespace App\Http\Controllers;

use App\Models\MessengerConversation;
use Illuminate\Http\Request;

/**
 * Class MessengerConversationController
 * @package App\Http\Controllers
 */
class MessengerConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messengerConversations = MessengerConversation::orderBy('id', 'desc')->paginate();

        return view('messenger-conversation.index', compact('messengerConversations'))
            ->with('i', (request()->input('page', 1) - 1) * $messengerConversations->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $messengerConversation = new MessengerConversation();
        return view('messenger-conversation.create', compact('messengerConversation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(MessengerConversation::$rules);

        $messengerConversation = MessengerConversation::create($request->all());

        return redirect()->route('messenger-conversations.index')
            ->with('success', 'MessengerConversation created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $messengerConversation = MessengerConversation::find($id);

        return view('messenger-conversation.show', compact('messengerConversation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $messengerConversation = MessengerConversation::find($id);

        return view('messenger-conversation.edit', compact('messengerConversation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  MessengerConversation $messengerConversation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MessengerConversation $messengerConversation)
    {
        request()->validate(MessengerConversation::$rules);

        $messengerConversation->update($request->all());

        return redirect()->route('messenger-conversations.index')
            ->with('success', 'MessengerConversation updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $messengerConversation = MessengerConversation::find($id)->delete();

        return redirect()->route('messenger-conversations.index')
            ->with('success', 'MessengerConversation deleted successfully');
    }
}
