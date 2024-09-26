<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateThemeRequest;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     */
    public function updateTheme(ProfileUpdateThemeRequest $request)
    {
        $request->user()->fill($request->validated());
        $request->user()->save();

        return response()->json([
            'status' => true,
            'data' => $request->user(),
        ], 200);
    }
}
