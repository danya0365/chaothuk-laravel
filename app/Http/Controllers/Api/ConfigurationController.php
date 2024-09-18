<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConfigurationCollection;
use App\Http\Resources\ConfigurationResource;
use App\Models\Configuration;
use Illuminate\Http\Request;

/**
 * Class ConfigurationController
 * @package App\Http\Controllers
 */
class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $configurations = Configuration::get();

        return response()->json([
            'status' => true,
            'data' => new ConfigurationCollection($configurations),
        ], 200);
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

        return response()->json([
            'status' => true,
            'data' => new ConfigurationResource($configuration)
        ], 201);
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

        return response()->json([
            'status' => true,
            'data' => new ConfigurationResource($configuration)
        ], 200);
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

        return response()->json([
            'status' => true,
            'data' => new ConfigurationResource($configuration)
        ], 200);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $configuration = Configuration::find($id)->delete();

        return response()->json([
            'status' => true,
            'data' => new ConfigurationResource($configuration)
        ], 200);
    }
}
