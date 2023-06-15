<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkType;
use Illuminate\Http\Request;

class WorkTypeController extends Controller
{
    /**
     * Get Work Types
     * @param Request $request
     * @return User 
     */
    public function getWorkTypes(Request $request)
    {
        $data = WorkType::orderBy('title', 'asc')->get();
        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }
}