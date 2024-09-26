<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Recruit;
use Illuminate\Http\Request;

class RecruitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recruits = Recruit::paginate();

        return view('backend.recruit.index', compact('recruits'))
            ->with('i', (request()->input('page', 1) - 1) * $recruits->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $recruit = new Recruit();
        return view('backend.recruit.create', compact('recruit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Recruit::$rules);

        $post = $request->all();
        $post["images"] = explode(',', $post["images"]);
        $post["images"] = array_map('trim', $post["images"]);
        $recruit = Recruit::create($post);

        return redirect()->route('backend.recruits.index')
            ->with('success', 'Recruit created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recruit = Recruit::find($id);

        return view('backend.recruit.show', compact('recruit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $recruit = Recruit::find($id);

        return view('backend.recruit.edit', compact('recruit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Recruit $recruit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recruit $recruit)
    {
        request()->validate(Recruit::$rules);

        $post = $request->all();
        $post["images"] = explode(',', $post["images"]);
        $post["images"] = array_map('trim', $post["images"]);
        $recruit->update($post);

        return redirect()->route('backend.recruits.index')
            ->with('success', 'Recruit updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $recruit = Recruit::find($id)->delete();

        return redirect()->route('backend.recruits.index')
            ->with('success', 'Recruit deleted successfully');
    }
}