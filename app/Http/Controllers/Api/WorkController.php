<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Models\Work;
use App\Models\WorkLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkController extends Controller
{
    /**
     * Get Works
     * @param Request $request
     * @return User 
     */
    public function getWorks(Request $request)
    {
        $data = Work::with(['author', 'province', 'workType'])
            ->orderBy('created_at', 'desc')
            ->limitOffset(request()->all())->get();
        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Get Top Hit Works
     * @param Request $request
     * @return User 
     */
    public function getTopHits(Request $request)
    {
        $data = Work::with(['author', 'province', 'workType'])
            ->orderBy('display_priority', 'desc')
            ->limitOffset(request()->all())->get();
        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createWork(Request $request)
    {
        $post = $request->all();
        // TODO: validate if user can create new work
        $post['author_id'] = $request->user()->id;

        $validatedRequest = Validator::make($post,  Work::$rules);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatedRequest->errors()
            ], 401);
        }

        $post["details"] = explode(',', $post["details"]);
        $post["details"] = array_map('trim', $post["details"]);
        $post["images"] = explode(',', $post["images"]);
        $post["images"] = array_map('trim', $post["images"]);

        try {
            $work = Work::create($post);
            return response()->json([
                'status' => true,
                'data' => $work,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createWorkLike(Request $request, Int $workId)
    {
        $post = [];
        // TODO: validate if user can like the work
        $post['author_id'] = $request->user()->id;
        $post['work_id'] = $workId;

        $validatedRequest = Validator::make($post,  [
            'work_id' => 'required|exists:works,id'
        ]);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatedRequest->errors()
            ], 401);
        }

        try {

            $workLike = WorkLike::create($post);
            $work = Work::find($post['work_id']);

            UserNotification::create(['']);
            return response()->json([
                'status' => true,
                'data' => $workLike,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
