<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class MeController extends Controller
{
    /**
     * Get Me
     * @param Request $request
     * @return User 
     */
    public function getMe(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => $request->user(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateMe(Request $request)
    {
        $post = $request->all();

        $validatedRequest = Validator::make($post, [
            'first_name' => 'nullable|min:2',
            'last_name' => 'nullable|min:2',
            //'profile_image' => 'nullable|min:2',
            //'cover_image' => 'nullable|min:2',
            //'birth_date' => 'required',
            //'mobile_phone' => 'required',
            //'location' => 'required',
            //'biography' => 'required',
        ]);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatedRequest->errors()
            ], 401);
        }

        try {
            $user = User::find($request->user()->id);
            $user->update($post);
            return response()->json([
                'status' => true,
                'data' => $user,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
