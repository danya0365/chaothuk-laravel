<?php

namespace App\Http\Controllers;

use App\Models\Geography;
use Illuminate\Http\Request;

/**
 * Class GeographyController
 * @package App\Http\Controllers
 */
class GeographyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $geographies = Geography::paginate();

        return view('geography.index', compact('geographies'))
            ->with('i', (request()->input('page', 1) - 1) * $geographies->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $geography = new Geography();
        return view('geography.create', compact('geography'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Geography::$rules);

        $geography = Geography::create($request->all());

        return redirect()->route('geographies.index')
            ->with('success', 'Geography created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $geography = Geography::find($id);

        return view('geography.show', compact('geography'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $geography = Geography::find($id);

        return view('geography.edit', compact('geography'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Geography $geography
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Geography $geography)
    {
        request()->validate(Geography::$rules);

        $geography->update($request->all());

        return redirect()->route('geographies.index')
            ->with('success', 'Geography updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $geography = Geography::find($id)->delete();

        return redirect()->route('geographies.index')
            ->with('success', 'Geography deleted successfully');
    }
}
