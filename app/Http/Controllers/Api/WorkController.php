<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function getWorks(Request $request)
    {
        $data = Work::with(['author', 'province', 'workType'])->paginate(request()->all());
        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }
}
