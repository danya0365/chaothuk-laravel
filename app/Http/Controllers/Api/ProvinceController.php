<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $provinces = Province::data();

        return response()->json([
            'status' => true,
            'data' => $provinces,
        ], 200);
    }
}
