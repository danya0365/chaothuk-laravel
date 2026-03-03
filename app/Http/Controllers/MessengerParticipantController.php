<?php

namespace App\Http\Controllers;

use App\Models\MessengerParticipant;
use Illuminate\Http\Request;

/**
 * Class MessengerParticipantController
 * @package App\Http\Controllers
 */
class MessengerParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messengerParticipants = MessengerParticipant::orderBy('id', 'desc')->paginate();

        return view('messenger-participant.index', compact('messengerParticipants'))
            ->with('i', (request()->input('page', 1) - 1) * $messengerParticipants->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $messengerParticipant = new MessengerParticipant();
        return view('messenger-participant.create', compact('messengerParticipant'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(MessengerParticipant::$rules);

        $messengerParticipant = MessengerParticipant::create($request->all());

        return redirect()->route('messenger-participants.index')
            ->with('success', 'MessengerParticipant created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $messengerParticipant = MessengerParticipant::find($id);

        return view('messenger-participant.show', compact('messengerParticipant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $messengerParticipant = MessengerParticipant::find($id);

        return view('messenger-participant.edit', compact('messengerParticipant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  MessengerParticipant $messengerParticipant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MessengerParticipant $messengerParticipant)
    {
        request()->validate(MessengerParticipant::$rules);

        $messengerParticipant->update($request->all());

        return redirect()->route('messenger-participants.index')
            ->with('success', 'MessengerParticipant updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $messengerParticipant = MessengerParticipant::find($id)->delete();

        return redirect()->route('messenger-participants.index')
            ->with('success', 'MessengerParticipant deleted successfully');
    }
}
