<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecruitBookingCollection;
use App\Http\Resources\UserLikeWorkCollection;
use App\Http\Resources\UserNotificationCollection;
use App\Http\Resources\UserResource;
use App\Http\Resources\WorkBookingCollection;
use App\Http\Resources\WorkCollection;
use App\Models\Recruit;
use App\Models\RecruitBooking;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\Work;
use App\Models\WorkBooking;
use App\Models\WorkLike;
use Carbon\Carbon;
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
            'data' => new UserResource($request->user()),
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
            'profile_image' => 'nullable|min:2',
            //'cover_image' => 'nullable|min:2',
            //'birth_date' => 'required',
            //'mobile_phone' => 'required',
            'location' => 'required',
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $post = $request->all();

        $validatedRequest = Validator::make($post, [
            'password' => 'required|between:6,30',
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
            $post['password'] = Hash::make(trim($post['password']));
            $user->update($post);
            return response()->json([
                'status' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Get User Notification
     * @param Request $request
     * @return User
     */
    public function getUserNotifications(Request $request)
    {
        $data = $request->user()->notifications()
            ->with('notificationable')
            ->orderBy('created_at', 'desc')
            ->limitOffset(request()->all())->get();

        return response()->json([
            'status' => true,
            'data' => new UserNotificationCollection($data),
        ], 200);
    }

    /**
     * Get Like Work
     * @param Request $request
     * @return User
     */
    public function getLikeWork(Request $request)
    {
        $data = $request->user()->likedWorks()
            ->with('author')
            ->with('province')
            ->with('workType')
            ->orderBy('created_at', 'desc')
            ->limitOffset(request()->all())->get();

        return response()->json([
            'status' => true,
            'data' => new WorkCollection($data),
        ], 200);
    }

    /**
     * Get Is Like Work
     * @param Request $request
     * @return User
     */
    public function getIsLikeWork(Request $request, $workId)
    {
        $data = $request->user()->likedWorks()
            ->where('works.id', $workId)->get();

        return response()->json([
            'status' => true,
            'data' => count($data) ? true : false,
        ], 200);
    }

    /**
     * Get Your Works
     * @param Request $request
     * @return User
     */
    public function getWorks(Request $request)
    {
        $query = $request->user()->works();

        $keyword = trim($request->get('keyword'));
        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%");
            });
        }

        $provinceId = trim($request->get('province_id'));
        if ($provinceId) {
            $query->where(function ($query) use ($provinceId) {
                $query->where('province_id', $provinceId);
            });
        }

        $dateFilter = trim($request->get('date'));
        if ($dateFilter) {
            $dateCarbon = Carbon::createFromFormat('Y-m-d', $dateFilter);
            $query->whereDate('created_at', '=', $dateCarbon);
        }

        $data = $query->orderBy('created_at', 'desc')
            ->limitOffset(request()->all())->get();
        return response()->json([
            'status' => true,
            'data' => new WorkCollection($data),
        ], 200);
    }

    /**
     * Get Your Recruits
     * @param Request $request
     * @return User
     */
    public function getRecruits(Request $request)
    {
        $query = $request->user()->recruits();

        $keyword = trim($request->get('keyword'));
        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%");
            });
        }

        $provinceId = trim($request->get('province_id'));
        if ($provinceId) {
            $query->where(function ($query) use ($provinceId) {
                $query->where('province_id', $provinceId);
            });
        }

        $dateFilter = trim($request->get('date'));
        if ($dateFilter) {
            $dateCarbon = Carbon::createFromFormat('Y-m-d', $dateFilter);
            $query->whereDate('created_at', '=', $dateCarbon);
        }

        $data = $query->orderBy('created_at', 'desc')
            ->limitOffset(request()->all())->get();
        return response()->json([
            'status' => true,
            'data' => new WorkCollection($data),
        ], 200);
    }

    /**
     * Get Work Booking
     * @param Request $request
     * @return User
     */
    public function getWorkBookings(Request $request)
    {
        $data = WorkBooking::with(['author', 'work'])->whereBelongsTo($request->user(), 'author')
            ->orderBy('created_at', 'desc')
            ->limitOffset(request()->all())->get();

        return response()->json([
            'status' => true,
            'data' => new WorkBookingCollection($data),
        ], 200);
    }

    /**
     * Get Recruit Booking
     * @param Request $request
     * @return User
     */
    public function getRecruitBookings(Request $request)
    {
        $data = RecruitBooking::with(['author', 'recruit'])->whereBelongsTo($request->user(), 'author')
            ->orderBy('created_at', 'desc')
            ->limitOffset(request()->all())->get();

        return response()->json([
            'status' => true,
            'data' => new RecruitBookingCollection($data),
        ], 200);
    }
}
