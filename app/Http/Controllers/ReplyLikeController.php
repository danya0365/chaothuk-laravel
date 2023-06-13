<?php

namespace App\Http\Controllers;

use App\Models\ReplyLike;
use Illuminate\Http\Request;

/**
 * Class ReplyLikeController
 * @package App\Http\Controllers
 */
class ReplyLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $replyLikes = ReplyLike::paginate();

        return view('reply-like.index', compact('replyLikes'))
            ->with('i', (request()->input('page', 1) - 1) * $replyLikes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $replyLike = new ReplyLike();
        return view('reply-like.create', compact('replyLike'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(ReplyLike::$rules);

        $replyLike = ReplyLike::create($request->all());

        return redirect()->route('reply-likes.index')
            ->with('success', 'ReplyLike created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $replyLike = ReplyLike::find($id);

        return view('reply-like.show', compact('replyLike'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $replyLike = ReplyLike::find($id);

        return view('reply-like.edit', compact('replyLike'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ReplyLike $replyLike
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReplyLike $replyLike)
    {
        request()->validate(ReplyLike::$rules);

        $replyLike->update($request->all());

        return redirect()->route('reply-likes.index')
            ->with('success', 'ReplyLike updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $replyLike = ReplyLike::find($id)->delete();

        return redirect()->route('reply-likes.index')
            ->with('success', 'ReplyLike deleted successfully');
    }
}
