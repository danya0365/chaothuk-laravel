<?php

namespace App\Http\Controllers;

use App\Models\SubDistrict;
use Illuminate\Http\Request;

/**
 * Class SubDistrictController
 * @package App\Http\Controllers
 */
class SubDistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subDistricts = SubDistrict::paginate();

        return view('sub-district.index', compact('subDistricts'))
            ->with('i', (request()->input('page', 1) - 1) * $subDistricts->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subDistrict = new SubDistrict();
        return view('sub-district.create', compact('subDistrict'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(SubDistrict::$rules);

        $subDistrict = SubDistrict::create($request->all());

        return redirect()->route('sub-districts.index')
            ->with('success', 'SubDistrict created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subDistrict = SubDistrict::find($id);

        return view('sub-district.show', compact('subDistrict'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subDistrict = SubDistrict::find($id);

        return view('sub-district.edit', compact('subDistrict'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  SubDistrict $subDistrict
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubDistrict $subDistrict)
    {
        request()->validate(SubDistrict::$rules);

        $subDistrict->update($request->all());

        return redirect()->route('sub-districts.index')
            ->with('success', 'SubDistrict updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $subDistrict = SubDistrict::find($id)->delete();

        return redirect()->route('sub-districts.index')
            ->with('success', 'SubDistrict deleted successfully');
    }
}
