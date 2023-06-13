<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

/**
 * Class DistrictController
 * @package App\Http\Controllers
 */
class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $districts = District::paginate();

        return view('district.index', compact('districts'))
            ->with('i', (request()->input('page', 1) - 1) * $districts->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $district = new District();
        return view('district.create', compact('district'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(District::$rules);

        $district = District::create($request->all());

        return redirect()->route('districts.index')
            ->with('success', 'District created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $district = District::find($id);

        return view('district.show', compact('district'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $district = District::find($id);

        return view('district.edit', compact('district'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  District $district
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, District $district)
    {
        request()->validate(District::$rules);

        $district->update($request->all());

        return redirect()->route('districts.index')
            ->with('success', 'District updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $district = District::find($id)->delete();

        return redirect()->route('districts.index')
            ->with('success', 'District deleted successfully');
    }
}
