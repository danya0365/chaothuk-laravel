<?php

namespace App\Http\Controllers;

use App\Models\WorkType;
use Illuminate\Http\Request;

/**
 * Class WorkTypeController
 * @package App\Http\Controllers
 */
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

        return view('work-type.index', compact('workTypes'))
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
        return view('work-type.create', compact('workType'));
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

        $workType = WorkType::create($request->all());

        return redirect()->route('work-types.index')
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

        return view('work-type.show', compact('workType'));
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

        return view('work-type.edit', compact('workType'));
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

        $workType->update($request->all());

        return redirect()->route('work-types.index')
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

        return redirect()->route('work-types.index')
            ->with('success', 'WorkType deleted successfully');
    }
}
