<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePushTokenRequest;
use App\Models\PushToken;
use Illuminate\Http\JsonResponse;

/**
 * Device push-token registration.
 *
 * The app calls this once per device after the user allows notifications: the
 * token is the phone's address, and re-registering the same device updates the
 * row instead of duplicating it, since a rolled token must not accumulate.
 */
class PushTokenController extends Controller
{
    public function store(StorePushTokenRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $token = PushToken::query()->updateOrCreate(
            ['token' => $validated['token']],
            [
                'user_id' => $request->user()->id,
                'platform' => $validated['platform'] ?? null,
            ],
        );

        return response()->json([
            'data' => [
                'id' => $token->id,
                'token' => $token->token,
                'platform' => $token->platform,
            ],
        ], 201);
    }
}
