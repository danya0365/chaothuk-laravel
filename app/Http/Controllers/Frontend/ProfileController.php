<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateThemeRequest;

/**
 * Frontend-only endpoints served under the /ajax prefix with the web session.
 *
 * Moved out of the Api namespace when the mobile API was split into its own
 * versioned surface, so the namespace now says where these endpoints live.
 */
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
