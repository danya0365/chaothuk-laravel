<?php

namespace App\Http\Controllers\Api;

use App\Enums\PersonType;
use App\Enums\Role;
use App\Events\ApiLogin;
use App\Events\ApiLogout;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiAuthLoginRequest;
use App\Http\Requests\ApiAuthCustomerRegisterLoginRequest;
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
    public function customerRegisterLogin(ApiAuthCustomerRegisterLoginRequest $request)
    {
        $post = $request->validated();

        $customerUser = UserCustomer::where(function ($q) use ($post) {
            $q->where(function ($q) use ($post) {
                $q->whereJsonContains('person_info', ['identification_no' => $post['id_card']])
                    ->whereJsonContains('person_info', ['birth_date' => $post['birth_date']])
                    ->whereJsonContains('person_info', ['mobile_phone' => $post['mobile_phone']]);
            });
            $q->orWhere(function ($q) use ($post) {
                $q->whereJsonContains('person_info', ['juristic_id' => $post['id_card']])
                    ->whereJsonContains('person_info', ['registration_date' => $post['birth_date']])
                    ->whereJsonContains('person_info', ['contact_number' => $post['mobile_phone']]);
            });
        })->first();
        if ($customerUser) {
            $loginUser = User::where('id', $customerUser->user_id)->first();
            if ($loginUser) {
                $token = $loginUser->createToken('my-app-token')->plainTextToken;
                ApiLogin::dispatch($loginUser);
                return response()->json(['data' => new AuthLoginResource(['user' => $loginUser, 'token' => $token]), 'status' => 1], 200);
            }
        }


        $findUser = User::where('email', $post['id_card'])->first();
        if ($findUser) {
            return response()->json(['message' => 'Incorrect credentials', 'status' => 0, 'data' => $customerUser], 401);
        }

        $registerUser = User::create([
            'name' => $post['id_card'],
            'email' => $post['id_card'],
            'password' => Hash::make($post['id_card']),
            'role_id' => Role::CUSTOMER->value,
        ]);

        if ($registerUser) {

            UserCustomer::create([
                'user_id' => $registerUser->id,
                'person_type' => PersonType::NATURAL->value,
                'person_info' => [
                    'first_name' => '',
                    'last_name' => '',
                    'identification_no' => $post['id_card'],
                    'birth_date' => $post['birth_date'],
                    'mobile_phone' => $post['mobile_phone']
                ]
            ]);

            $token = $registerUser->createToken('my-app-token')->plainTextToken;
            event(new Registered($registerUser));

            return response()->json(['data' => new AuthRegisterResource(['user' => $registerUser, 'token' => $token]), 'status' => 1], 201);
        }
        return response()->json(['message' => 'Incorrect credentials', 'status' => 0], 401);
    }


    public function register(ApiAuthRegisterRequest $request)
    {
        $post = $request->validated();

        $user = User::create([
            'name' => $post['name'],
            'email' => $post['email'],
            'password' => Hash::make($post['password']),
            'role_id' => Role::CUSTOMER->value,
        ]);

        if ($user) {
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
        $user = auth('sanctum')->user();
        if ($user) {
            $userInfo = User::with('role')->with('customer')->with('merchant')->with('userTypeMaps', function ($q) {
                $q->with('userType');
            })->where('id', $user->id)->first();
            return response()->json(['data' => new AuthUserResource($userInfo), 'status' => 1], 200);
        }
        return response()->json(['message' => 'not login', 'status' => 0], 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'not login', 'status' => 0], 401);
        }
        $user->currentAccessToken()->delete();

        ApiLogout::dispatch($user);

        return response()->json(['message' => 'Success', 'status' => 1], 200);
    }

    public function revokeToken(Request $request)
    {
        $token = $request->get('token');
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'not login', 'status' => 0], 401);
        }
        $user->tokens()->where('token', $token)->delete();
        ApiLogout::dispatch($user);
        return response()->json(['message' => 'Success', 'status' => 1], 200);
    }
}
