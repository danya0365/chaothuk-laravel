<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProvinceCollection;
use App\Models\Province;

/**
 * Class ProvinceController
 * @package App\Http\Controllers
 */
class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $data = Province::orderBy('id', 'asc')->get();
        return response()->json([
            'status' => true,
            'data' => new ProvinceCollection($data),
        ], 200);
    }
}