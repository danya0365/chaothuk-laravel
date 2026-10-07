<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Role;
use App\Events\ApiLogin;
use App\Events\ApiLogout;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Mobile authentication.
 *
 * Returns standard HTTP codes — 201 on register, 401 on bad credentials — so the
 * client never has to read the body to know what happened.
 */
class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $post = $request->validated();

        $user = User::create([
            'name' => $post['name'],
            'email' => $post['email'],
            'password' => Hash::make($post['password']),
            'first_name' => $post['first_name'],
            'last_name' => $post['last_name'],
            'mobile_phone' => $post['mobile_phone'] ?? null,
        ]);

        $user->roles()->sync([Role::MEMBER->value]);

        event(new Registered($user));

        return response()->json([
            'data' => [
                'user' => new UserResource($user->load('roles.permissions', 'permissions')),
                'token' => $user->createToken('mobile-app')->plainTextToken,
            ],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $post = $request->validated();

        $user = User::where('email', $post['email'])->first();

        if (! $user || ! Hash::check($post['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['อีเมลหรือรหัสผ่านไม่ถูกต้อง'],
            ]);
        }

        ApiLogin::dispatch($user);

        return response()->json([
            'data' => [
                'user' => new UserResource($user->load('roles.permissions', 'permissions')),
                'token' => $user->createToken('mobile-app')->plainTextToken,
            ],
        ]);
    }

    /**
     * The signed-in user, with permissions so the app can gate its create buttons.
     */
    public function me(Request $request): UserResource
    {
        return new UserResource($request->user()->load('roles.permissions', 'permissions'));
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->currentAccessToken()?->delete();

        ApiLogout::dispatch($user);

        return response()->json(['message' => 'ออกจากระบบสำเร็จ']);
    }
}
