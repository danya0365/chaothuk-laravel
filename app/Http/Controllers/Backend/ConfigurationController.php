<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Traits\SelectOption;
use Illuminate\Http\Request;

/**
 * Class ConfigurationController
 * @package App\Http\Controllers
 */
class ConfigurationController extends Controller
{
    use SelectOption;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configurations = Configuration::orderBy('id', 'asc')->paginate()->withQueryString();

        return view('backend.configuration.index', compact('configurations'))
            ->with('i', (request()->input('page', 1) - 1) * $configurations->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $configuration = new Configuration();
        $valueTypeSelections = $this->configurationValueType();
        return view('backend.configuration.create', compact('configuration', 'valueTypeSelections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Configuration::$rules);

        $configuration = Configuration::create($request->all());

        return redirect()->route('backend.configurations.index')
            ->with('success', 'Configuration created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $configuration = Configuration::find($id);

        return view('backend.configuration.show', compact('configuration'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $configuration = Configuration::find($id);
        $valueTypeSelections = $this->configurationValueType();
        return view('backend.configuration.edit', compact('configuration', 'valueTypeSelections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Configuration $configuration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Configuration $configuration)
    {
        request()->validate(Configuration::$rules);

        $configuration->update($request->all());

        return redirect()->route('backend.configurations.index')
            ->with('success', 'Configuration updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $configuration = Configuration::find($id)->delete();

        return redirect()->route('backend.configurations.index')
            ->with('success', 'Configuration deleted successfully');
    }
}
