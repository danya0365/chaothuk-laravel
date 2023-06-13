<?php

namespace App\Http\Controllers;

use App\Models\WorkLike;
use Illuminate\Http\Request;

/**
 * Class WorkLikeController
 * @package App\Http\Controllers
 */
class WorkLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workLikes = WorkLike::paginate();

        return view('work-like.index', compact('workLikes'))
            ->with('i', (request()->input('page', 1) - 1) * $workLikes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $workLike = new WorkLike();
        return view('work-like.create', compact('workLike'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(WorkLike::$rules);

        $workLike = WorkLike::create($request->all());

        return redirect()->route('work-likes.index')
            ->with('success', 'WorkLike created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $workLike = WorkLike::find($id);

        return view('work-like.show', compact('workLike'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $workLike = WorkLike::find($id);

        return view('work-like.edit', compact('workLike'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  WorkLike $workLike
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkLike $workLike)
    {
        request()->validate(WorkLike::$rules);

        $workLike->update($request->all());

        return redirect()->route('work-likes.index')
            ->with('success', 'WorkLike updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $workLike = WorkLike::find($id)->delete();

        return redirect()->route('work-likes.index')
            ->with('success', 'WorkLike deleted successfully');
    }
}
