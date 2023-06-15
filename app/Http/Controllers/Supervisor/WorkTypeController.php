<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\WorkType;
use Illuminate\Http\Request;

class WorkTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workTypes = WorkType::paginate();

        return view('supervisor.work-type.index', compact('workTypes'))
            ->with('i', (request()->input('page', 1) - 1) * $workTypes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $workType = new WorkType();
        return view('supervisor.work-type.create', compact('workType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(WorkType::$rules);

        $post = $request->all();
        $post["details"] = explode(',', $post["details"]);
        $post["details"] = array_map('trim', $post["details"]);
        $post["images"] = explode(',', $post["images"]);
        $post["images"] = array_map('trim', $post["images"]);
        $workType = WorkType::create($post);

        return redirect()->route('supervisor.work-types.index')
            ->with('success', 'WorkType created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $workType = WorkType::find($id);

        return view('supervisor.work-type.show', compact('workType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $workType = WorkType::find($id);

        return view('supervisor.work-type.edit', compact('workType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  WorkType $workType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkType $workType)
    {
        request()->validate(WorkType::$rules);

        $post = $request->all();
        $post["details"] = explode(',', $post["details"]);
        $post["details"] = array_map('trim', $post["details"]);
        $post["images"] = explode(',', $post["images"]);
        $post["images"] = array_map('trim', $post["images"]);
        $workType->update($post);

        return redirect()->route('supervisor.work-types.index')
            ->with('success', 'WorkType updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $workType = WorkType::find($id)->delete();

        return redirect()->route('supervisor.work-types.index')
            ->with('success', 'WorkType deleted successfully');
    }
}