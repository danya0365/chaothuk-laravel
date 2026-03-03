<?php

namespace App\Http\Controllers\Api;

use App\Enums\BookingStatus;
use App\Enums\NotificationType;
use App\Enums\Permission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WorkRequest;
use App\Http\Resources\TopHitWorkCollection;
use App\Http\Resources\UserCollection;
use App\Http\Resources\WorkBookingCollection;
use App\Http\Resources\WorkCollection;
use App\Http\Resources\WorkResource;
use App\Models\UserNotification;
use App\Models\Work;
use App\Models\WorkBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class WorkController extends Controller
{
    /**
     * Get Works
     * @param Request $request
     * @return User
     */
    public function getWorks(Request $request)
    {
        $query = Work::with(['author', 'province', 'workType', 'categories']);

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
     * Get Work Detail
     * @param Request $request
     * @return Work
     */
    public function getWork(Request $request, $workId)
    {
        $data = Work::with(['author', 'province', 'workType', 'categories'])->find($workId);
        return response()->json([
            'status' => $data ? true : false,
            'data' => $data ? new WorkResource($data) : null,
        ], 200);
    }

    /**
     * Get Work Like
     * @param Request $request
     * @return User
     */
    public function getWorkLikes(Request $request, $workId)
    {
        $work = Work::find($workId);
        if (!$work) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 200);
        }

        $data = $work->userLikes()
            ->orderBy('created_at', 'desc')
            ->limitOffset(request()->all())->get();

        return response()->json([
            'status' => true,
            'data' => new UserCollection($data),
        ], 200);
    }

    /**
     * Get Work Like Count
     * @param Request $request
     * @return User
     */
    public function getWorkLikeCount(Request $request, $workId)
    {
        $work = Work::find($workId);
        if (!$work) {
            return response()->json([
                'status' => false,
                'data' => null,
            ], 200);
        }

        $count = $work->userLikes()
            ->orderBy('created_at', 'desc')
            ->count();

        return response()->json([
            'status' => true,
            'data' => $count,
        ], 200);
    }

    /**
     * Get Top Hit Works
     * @param Request $request
     * @return User
     */
    public function getTopHits(Request $request)
    {
        $data = Work::with(['author', 'province', 'workType', 'categories'])
            ->orderBy('display_priority', 'desc')
            ->limitOffset(request()->all())->get();
        return response()->json([
            'status' => true,
            'data' => new TopHitWorkCollection($data),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createWork(WorkRequest $request)
    {
        /** @var \App\Models/User $user */
        $user = auth('sanctum')->user();

        if (!$user->isPermission(Permission::CREATE_WORK->value)) {
            return response()->json([
                'status' => false,
                'message' => 'no permission',
            ], 403);
        }

        $request->validated();

        $post = $request->all();
        $post['author_id'] = $user->id;

        if (isset($post["details"]) && trim($post["details"]) != "") {
            $post["details"] = explode(',', $post["details"]);
            $post["details"] = array_map('trim', $post["details"]);
            $post["details"] = array_filter($post["details"]);
        } else {
            $post["details"] = [];
        }

        if (isset($post["images"]) && is_array($post["images"])) {
            $post["images"] = array_map('trim', $post["images"]);
            $post["images"] = array_filter($post["images"]);
        } else {
            $post["images"] = [];
        }

        try {
            $work = Work::create($post);
            return response()->json([
                'status' => true,
                'data' => $work,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateWork(WorkRequest $request, $id)
    {
        /** @var \App\Models/User $user */
        $user = auth('sanctum')->user();
        $request->validated();

        $post = $request->all();
        $post['author_id'] = $user->id;

        if (isset($post["details"]) && trim($post["details"]) != "") {
            $post["details"] = explode(',', $post["details"]);
            $post["details"] = array_map('trim', $post["details"]);
            $post["details"] = array_filter($post["details"]);
        } else {
            $post["details"] = [];
        }

        if (isset($post["images"]) && is_array($post["images"])) {
            $post["images"] = array_map('trim', $post["images"]);
            $post["images"] = array_filter($post["images"]);
        }

        try {
            $work = Work::find($id);

            if ($user->id != $work->author_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'no permission',
                ], 403);
            }

            $work->update($post);
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

        $validatedRequest = Validator::make($post, [
            'work_id' => 'required|exists:works,id'
        ]);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => implode(",", $validatedRequest->messages()->all()),
                'errors' => $validatedRequest->errors()
            ], 401);
        }

        try {

            $user = $request->user();
            $work = Work::with('author')->find($post['work_id']);

            if ($user->likedWorks()->get()->contains($work)) {
                $user->likedWorks()->detach($work->id);
            } else {
                $user->likedWorks()->attach($work->id);

                $userNotification = UserNotification::whereHasMorph(
                    'notificationable',
                    [Work::class],
                    function (Builder $query, string $type) use ($work) {
                        $query->where('id', $work->id);
                    }
                )
                    ->where('notification_type', NotificationType::WORK_LIKE->value)
                    ->whereBelongsTo($work->author, 'author')
                    ->first();
                if ($userNotification) {
                    $details = $userNotification->details;
                    $details['count'] = $details['count'] + 1;
                    $userNotification->details = $details;
                    $userNotification->is_read = false;
                    $userNotification->save();
                } else {
                    $userNotification = new UserNotification();
                    $userNotification->title = "มีคนชื่นชอบงานของคุณ";
                    $userNotification->details = ['count' => 1];
                    $userNotification->notification_type = NotificationType::WORK_LIKE->value;
                    $userNotification->notificationable()->associate($work);
                    $work->author->notifications()->save($userNotification);
                }
            }


            $work->like_count = $work->userLikes()->count();
            $work->save();

            return response()->json([
                'status' => true,
                'message' => 'success',
            ], 201);
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
    public function createWorkBooking(Request $request, Int $workId)
    {
        $post = $request->only(['customer_message', 'mobile_phone', 'booking_date']);
        // TODO: validate if user can like the work
        $post['author_id'] = $request->user()->id;
        $post['work_id'] = $workId;

        $validatedRequest = Validator::make($post, [
            'work_id' => 'required|exists:works,id',
            'customer_message' => 'required',
            'mobile_phone' => 'required',
            'booking_date' => 'required|date_format:Y-m-d',
        ]);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => implode(",", $validatedRequest->messages()->all()),
                'errors' => $validatedRequest->errors()
            ], 401);
        }

        try {

            $user = $request->user();
            $work = Work::with('author')->find($post['work_id']);

            if ($user->bookedWorks()->get()->contains($work)) {
                //
            } else {
                $user->bookedWorks()->attach(
                    $work->id,
                    $post
                );

                $userNotification = UserNotification::whereHasMorph(
                    'notificationable',
                    [Work::class],
                    function (Builder $query) use ($work) {
                        $query->where('id', $work->id);
                    }
                )
                    ->where('notification_type', NotificationType::WORK_BOOKING->value)
                    ->whereBelongsTo($work->author, 'author')
                    ->first();
                if ($userNotification) {
                    $details = $userNotification->details;
                    $details['count'] = $details['count'] + 1;
                    $userNotification->details = $details;
                    $userNotification->is_read = false;
                    $userNotification->save();
                } else {
                    $userNotification = new UserNotification();
                    $userNotification->title = "มีคนจองงานของคุณ";
                    $userNotification->details = ['count' => 1];
                    $userNotification->notification_type = NotificationType::WORK_BOOKING->value;
                    $userNotification->notificationable()->associate($work);
                    $work->author->notifications()->save($userNotification);
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Get Work Booking
     * @param Request $request
     * @return User
     */
    public function getWorkBookings(Request $request, $workId)
    {
        $data = Work::find($workId)->bookings()
            ->orderBy('created_at', 'desc')
            ->limitOffset(request()->all())->get();

        return response()->json([
            'status' => true,
            'data' => new WorkBookingCollection($data),
        ], 200);
    }

    /**
     * Get Confirm Work Booking
     * @param Request $request
     * @return User
     */
    public function getConfirmWorkBookings(Request $request, $workId)
    {
        $query = Work::find($workId)->bookings()
            ->where('booking_status', BookingStatus::CONFIRM->value);

        $dateStart = trim($request->get('date_start'));
        $dateEnd = trim($request->get('date_end'));
        if ($dateStart && $dateEnd) {
            $dateStartCarbon = Carbon::createFromFormat('Y-m-d', $dateStart);
            $dateEndCarbon = Carbon::createFromFormat('Y-m-d', $dateEnd);
            $query->whereBetween(DB::raw('DATE(booking_date)'), [$dateStartCarbon, $dateEndCarbon]);
        }

        $data = $query->orderBy('created_at', 'desc')
            ->limitOffset(request()->all())->get();

        return response()->json([
            'status' => true,
            'data' => new WorkBookingCollection($data),
        ], 200);
    }
}
