<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Permission;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkRequest;
use App\Models\Work;
use App\Traits\SelectOption;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    use SelectOption;
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
        $provinceSelections = $this->province();
        $workTypeSelections = $this->workType();
        $userSelections = $this->user();
        return view('backend.work.create', compact('work', 'provinceSelections', 'workTypeSelections', 'userSelections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkRequest $request)
    {
        /** @var \App\Models/User $authUser */
        $authUser = auth()->user();

        if (!$authUser->isPermission(Permission::CREATE_WORK->value)) {
            return response()->json([
                'status' => false,
                'message' => 'no permission',
            ], 403);
        }

        $request->validated();
        $post = $request->all();

        if (isset($post["details"]) && trim($post["details"]) != "") {
            $post["details"] = explode(',', $post["details"]);
            $post["details"] = array_map('trim', $post["details"]);
        } else {
            $post["details"] = [];
        }

        // if (isset($post["images"]) && trim($post["images"]) != "") {
        //     $post["images"] = explode(',', $post["images"]);
        //     $post["images"] = array_map('trim', $post["images"]);
        // } else {
        //     $post["images"] = [];
        // }

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
        $provinceSelections = $this->province();
        $workTypeSelections = $this->workType();
        $userSelections = $this->user();
        return view('backend.work.edit', compact('work', 'provinceSelections', 'workTypeSelections', 'userSelections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Work $work
     * @return \Illuminate\Http\Response
     */
    public function update(WorkRequest $request, Work $work)
    {
        $request->validated();
        $post = $request->all();

        $post = $request->all();
        if (isset($post["details"]) && trim($post["details"]) != "") {
            $post["details"] = explode(',', $post["details"]);
            $post["details"] = array_map('trim', $post["details"]);
        } else {
            $post["details"] = [];
        }

        // if (isset($post["images"]) && trim($post["images"]) != "") {
        //     $post["images"] = explode(',', $post["images"]);
        //     $post["images"] = array_map('trim', $post["images"]);
        // } else {
        //     $post["images"] = [];
        // }

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
