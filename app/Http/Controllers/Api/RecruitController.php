<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecruitCollection;
use App\Models\Recruit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecruitController extends Controller
{
    /**
     * Get Recruits
     * @param Request $request
     * @return User 
     */
    public function getRecruits(Request $request)
    {
        $data = Recruit::with(['author', 'province', 'workType'])
            ->orderBy('created_at', 'desc')
            ->limitOffset(request()->all())->get();
        return response()->json([
            'status' => true,
            'data' => new RecruitCollection($data),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createRecruit(Request $request)
    {
        $post = $request->all();
        // TODO: validate if user can create new work
        $post['author_id'] = $request->user()->id;

        $validatedRequest = Validator::make($post,  Recruit::$rules);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatedRequest->errors()
            ], 401);
        }

        $post["images"] = explode(',', $post["images"]);
        $post["images"] = array_map('trim', $post["images"]);

        try {
            $recruit = Recruit::create($post);
            return response()->json([
                'status' => true,
                'data' => $recruit,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
