<?php

namespace App\Http\Controllers\Api;

use App\Enums\NotificationType;
use App\Enums\Permission;
use App\Http\Controllers\Controller;
use App\Http\Resources\RecruitBookingCollection;
use App\Http\Resources\RecruitCollection;
use App\Http\Resources\RecruitResource;
use App\Models\Recruit;
use App\Models\UserNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecruitController extends Controller
{
    /**
     * Get Recruits
     * @param Request $request
     * @return User
     */
    public function getRecruits(Request $request)
    {
        $post = $request->all();
        $query = Recruit::with(['author', 'province', 'workType']);

        if ($post['exceptAuthorId']) {
            $query->where('author_id', '!=', $post['exceptAuthorId']);
        }

        $data = $query->orderBy('created_at', 'desc')
        ->limitOffset(request()->all())->get();
        return response()->json([
            'status' => true,
            'data' => new RecruitCollection($data),
        ], 200);
    }

    /**
     * Get Work Detail
     * @param Request $request
     * @return User
     */
    public function getRecruit(Request $request, $recruitId)
    {
        $data = Recruit::with(['author', 'province', 'workType'])->find($recruitId);
        return response()->json([
            'status' => true,
            'data' => new RecruitResource($data),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createRecruit(Request $request)
    {
        $post = $request->all();

        /** @var User $user */
        $user = auth('sanctum')->user();

        if (!$user->isPermission(Permission::CREATE_RECRUIT->value)) {
            return response()->json([
                'status' => false,
                'message' => 'no permission',
            ], 403);
        }

        $post['author_id'] = $user->id;

        $validatedRequest = Validator::make($post, Recruit::$rules);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatedRequest->errors()
            ], 401);
        }

        $post["images"] = explode(',', $post["images"]);
        $post["images"] = array_map('trim', $post["images"]);
        $post["images"] = array_filter($post["images"]);

        try {
            $recruit = Recruit::create($post);
            return response()->json([
                'status' => true,
                'data' => $recruit,
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
    public function updateRecruit(Request $request, $id)
    {
        $post = $request->all();
        /** @var User $user */
        $user = auth('sanctum')->user();

        $post['author_id'] = $user->id;

        $validatedRequest = Validator::make($post, Recruit::$rules);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatedRequest->errors()
            ], 401);
        }

        $post["images"] = explode(',', $post["images"]);
        $post["images"] = array_map('trim', $post["images"]);
        $post["images"] = array_filter($post["images"]);

        try {
            $recruit = Recruit::find($id);

            if ($user->id != $recruit->author_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'no permission',
                ], 403);
            }

            $recruit->update($post);
            return response()->json([
                'status' => true,
                'data' => $recruit,
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
    public function createRecruitBooking(Request $request, Int $recruitId)
    {
        /** @var User $user */
        $user = $request->user();

        $post = $request->only(['customer_message', 'mobile_phone', 'booking_date']);
        // TODO: validate if user can like the work
        $post['author_id'] = $request->user()->id;
        $post['recruit_id'] = $recruitId;

        $validatedRequest = Validator::make($post, [
            'recruit_id' => 'required|exists:recruits,id',
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

            $recruit = Recruit::with('author')->find($post['recruit_id']);

            if ($user->bookedRecruits()->get()->contains($recruit)) {
                //
            } else {
                $user->bookedRecruits()->attach(
                    $recruit->id,
                    $post
                );

                $userNotification = UserNotification::whereHasMorph(
                    'notificationable',
                    [Recruit::class],
                    function (Builder $query) use ($recruit) {
                        $query->where('id', $recruit->id);
                    }
                )
                    ->where('notification_type', NotificationType::RECRUIT_BOOKING->value)
                    ->whereBelongsTo($recruit->author, 'author')
                    ->first();
                if ($userNotification) {
                    $details = $userNotification->details;
                    $details['count'] = $details['count'] + 1;
                    $userNotification->details = $details;
                    $userNotification->is_read = false;
                    $userNotification->save();
                } else {
                    $userNotification = new UserNotification();
                    $userNotification->title = "มีคนจองโพสท์หางานของคุณ";
                    $userNotification->details = ['count' => 1];
                    $userNotification->notification_type = NotificationType::RECRUIT_BOOKING->value;
                    $userNotification->notificationable()->associate($recruit);
                    $recruit->author->notifications()->save($userNotification);
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
    public function getRecruitBookings(Request $request, $recruitId)
    {
        $data = Recruit::find($recruitId)->bookings()
            ->orderBy('created_at', 'desc')
            ->limitOffset(request()->all())->get();

        return response()->json([
            'status' => true,
            'data' => new RecruitBookingCollection($data),
        ], 200);
    }
}
