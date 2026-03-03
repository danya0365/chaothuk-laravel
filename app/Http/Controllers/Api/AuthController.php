<?php

namespace App\Http\Controllers\Api;

use App\Enums\Role;
use App\Events\ApiLogin;
use App\Events\ApiLogout;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiAuthLoginRequest;
use App\Http\Requests\ApiAuthRegisterRequest;
use App\Http\Resources\AuthLoginResource;
use App\Http\Resources\AuthRegisterResource;
use App\Http\Resources\AuthUserResource;
use App\Models\User;
use App\Models\UserCustomer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    public function register(ApiAuthRegisterRequest $request)
    {
        $post = $request->validated();

        $user = User::create([
            'name' => $post['name'],
            'email' => $post['email'],
            'password' => Hash::make($post['password']),
            'first_name' => $post['first_name'],
            'last_name' => $post['last_name'],
            'profile_image' => $post['profile_image'] ?? ''
        ]);

        if ($user) {

            $user->roles()->sync(['role_id' => Role::MEMBER->value]);

            $token = $user->createToken('my-app-token')->plainTextToken;
            event(new Registered($user));

            return response()->json(['data' => new AuthRegisterResource(['user' => $user, 'token' => $token]), 'status' => 1], 201);
        }

        return response()->json(['message' => 'Incorrect credentials', 'status' => 0], 401);
    }

    public function login(ApiAuthLoginRequest $request)
    {
        $post = $request->validated();

        if (Auth::attempt(['email' => $post['email'], 'password' => $post['password']])) {
            $user = $request->user();
            $token = $user->createToken('my-app-token')->plainTextToken;

            ApiLogin::dispatch($user);

            return response()->json(['data' => new AuthLoginResource(['user' => $user, 'token' => $token]), 'status' => 1], 200);
        }

        return response()->json(['message' => 'Incorrect credentials', 'status' => 0], 401);
    }

    public function user(Request $request)
    {
        /** @var \App\Models/User $user */
        $user = auth('sanctum')->user();
        if ($user) {
            $userInfo = User::with(['roles' => function ($q) {
                return $q->with('permissions');
            }])->with('permissions')->where('id', $user->id)->first();
            return response()->json(['data' => new AuthUserResource($userInfo), 'status' => 1], 200);
        }
        return response()->json(['message' => 'not login', 'status' => 0], 401);
    }

    public function logout(Request $request)
    {
        /** @var \App\Models/User $user */
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'not login', 'status' => 0], 401);
        }

        $user->currentAccessToken()->delete();

        auth()->guard('web')->logout();

        ApiLogout::dispatch($user);

        return response()->json(['message' => 'Success', 'status' => 1], 200);
    }

    public function revokeToken(Request $request)
    {
        $token = $request->get('token');
        /** @var \App\Models/User $user */
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'not login', 'status' => 0], 401);
        }
        $user->tokens()->where('token', $token)->delete();
        ApiLogout::dispatch($user);
        return response()->json(['message' => 'Success', 'status' => 1], 200);
    }
}
