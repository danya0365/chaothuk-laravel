<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;

/**
 * Class ReplyController
 * @package App\Http\Controllers
 */
class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $replies = Reply::paginate();

        return view('reply.index', compact('replies'))
            ->with('i', (request()->input('page', 1) - 1) * $replies->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $reply = new Reply();
        return view('reply.create', compact('reply'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Reply::$rules);

        $reply = Reply::create($request->all());

        return redirect()->route('replies.index')
            ->with('success', 'Reply created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reply = Reply::find($id);

        return view('reply.show', compact('reply'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reply = Reply::find($id);

        return view('reply.edit', compact('reply'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
        request()->validate(Reply::$rules);

        $reply->update($request->all());

        return redirect()->route('replies.index')
            ->with('success', 'Reply updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $reply = Reply::find($id)->delete();

        return redirect()->route('replies.index')
            ->with('success', 'Reply deleted successfully');
    }
}
