<?php

namespace App\Http\Controllers\Api;

use App\Enums\NotificationType;
use App\Http\Controllers\Controller;
use App\Http\Resources\TopHitWorkCollection;
use App\Http\Resources\UserCollection;
use App\Http\Resources\WorkCollection;
use App\Http\Resources\WorkResource;
use App\Models\UserNotification;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;

class WorkController extends Controller
{
    /**
     * Get Works
     * @param Request $request
     * @return User 
     */
    public function getWorks(Request $request)
    {
        $query = Work::with(['author', 'province', 'workType']);

        $keyword = trim($request->get('keyword'));
        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE',  "%{$keyword}%");
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
            $dateCarbon = Carbon::createFromFormat('Y-m-d',  $dateFilter);
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
     * @return User 
     */
    public function getWork(Request $request, $workId)
    {
        $data = Work::with(['author', 'province', 'workType'])->find($workId);
        return response()->json([
            'status' => true,
            'data' => new WorkResource($data),
        ], 200);
    }

    /**
     * Get Work Like
     * @param Request $request
     * @return User 
     */
    public function getWorkLikes(Request $request, $workId)
    {
        $data = Work::find($workId)->userLikes()
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
        $count = Work::find($workId)->userLikes()
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
        $data = Work::with(['author', 'province', 'workType'])
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
    public function createWork(Request $request)
    {
        $post = $request->all();
        // TODO: validate if user can create new work
        $post['author_id'] = $request->user()->id;

        $validatedRequest = Validator::make($post,  Work::$rules, [
            'code.unique' => trans('validation.work_code_unique')
        ]);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => implode(",", $validatedRequest->messages()->all()),
                'errors' => $validatedRequest->errors()
            ], 401);
        }

        if (isset($post["details"]) && trim($post["details"]) != "") {
            $post["details"] = explode(',', $post["details"]);
            $post["details"] = array_map('trim', $post["details"]);
            $post["details"] = array_filter($post["details"]);
        } else {
            $post["details"] = [];
        }

        if (isset($post["images"]) && trim($post["images"]) != "") {
            $post["images"] = explode(',', $post["images"]);
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
                    ->where('notification_type', NotificationType::WorkLike())
                    ->whereBelongsTo($user, 'author')
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
                    $userNotification->notification_type = NotificationType::WorkLike();
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

        $validatedRequest = Validator::make($post,  [
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
                )->where('notification_type', NotificationType::WorkBooking())->first();
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
                    $userNotification->notification_type = NotificationType::WorkBooking();
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
}
