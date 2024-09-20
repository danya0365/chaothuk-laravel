<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $works = Work::paginate();

        return view('backend.work.index', compact('works'))
            ->with('i', (request()->input('page', 1) - 1) * $works->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $work = new Work();
        return view('backend.work.create', compact('work'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Work::$rules);

        $post = $request->all();
        if (isset($post["details"]) && trim($post["details"]) != "") {
            $post["details"] = explode(',', $post["details"]);
            $post["details"] = array_map('trim', $post["details"]);
        } else {
            $post["details"] = [];
        }

        if (isset($post["images"]) && trim($post["images"]) != "") {
            $post["images"] = explode(',', $post["images"]);
            $post["images"] = array_map('trim', $post["images"]);
        } else {
            $post["images"] = [];
        }
        $work = Work::create($post);

        return redirect()->route('backend.works.index')
            ->with('success', 'Work created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $work = Work::find($id);

        return view('backend.work.show', compact('work'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $work = Work::find($id);

        return view('backend.work.edit', compact('work'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Work $work
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Work $work)
    {
        request()->validate(Work::$rules);

        $post = $request->all();
        if (isset($post["details"]) && trim($post["details"]) != "") {
            $post["details"] = explode(',', $post["details"]);
            $post["details"] = array_map('trim', $post["details"]);
        } else {
            $post["details"] = [];
        }

        if (isset($post["images"]) && trim($post["images"]) != "") {
            $post["images"] = explode(',', $post["images"]);
            $post["images"] = array_map('trim', $post["images"]);
        } else {
            $post["images"] = [];
        }
        $work->update($post);

        return redirect()->route('backend.works.index')
            ->with('success', 'Work updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $work = Work::find($id)->delete();

        return redirect()->route('backend.works.index')
            ->with('success', 'Work deleted successfully');
    }
}