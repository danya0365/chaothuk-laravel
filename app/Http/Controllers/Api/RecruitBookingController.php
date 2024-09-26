<?php

namespace App\Http\Controllers\Api;

use App\Enums\ConfirmStatus;
use App\Enums\NotificationType;
use App\Http\Controllers\Controller;
use App\Http\Resources\RecruitBookingResource;
use App\Models\Recruit;
use App\Models\RecruitBooking;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecruitBookingController extends Controller
{
    /**
     * Get RecruitBooking Detail
     * @param Request $request
     * @return RecruitBooking
     */
    public function show(Request $request, $id)
    {
        $data = RecruitBooking::with('recruit')->with('author')->find($id);
        return response()->json([
            'status' => $data ? true : false,
            'data' => $data ? new RecruitBookingResource($data) : null,
        ], 200);
    }

    /**
     * Do Worker Confirm
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function doWorkerConfirm(Request $request, Int $recruitBookingId)
    {
        $post = ['worker_confirm_status' => ConfirmStatus::CONFIRM->value];
        $post['user_id'] = $request->user()->id;
        $post['recruit_booking_id'] = $recruitBookingId;

        $validatedRequest = Validator::make($post, [
            'recruit_booking_id' => 'required|exists:recruit_bookings,id',
            'user_id' => 'required',
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
            $recruitBooking = RecruitBooking::with('recruit')->with('author')->whereHas('recruit', function ($query) use ($user) {
                $query->with('author')->whereBelongsTo($user, 'author');
            })->find($recruitBookingId);

            if (!$recruitBooking) {
                return response()->json([
                    'status' => false,
                    'message' => 'no permission'
                ], 403);
            }

            $recruitBooking->worker_confirm_status = ConfirmStatus::CONFIRM->value;
            $recruitBooking->save();

            if ($recruitBooking->worker_confirm_status == $recruitBooking->customer_confirm_status) {
                $recruitBooking->booking_status = ConfirmStatus::CONFIRM->value;
                $recruitBooking->save();


                $userNotification = UserNotification::whereHasMorph(
                    'notificationable',
                    [Recruit::class],
                    function ($query) use ($recruitBooking) {
                        $query->where('id', $recruitBooking->recruit->id);
                    }
                )
                    ->where('notification_type', NotificationType::RECRUIT_BOOKING_CONFIRM->value)
                    ->whereBelongsTo($recruitBooking->author, 'author')
                    ->first();
                if ($userNotification) {
                    $details = $userNotification->details;
                    $details['count'] = $details['count'] + 1;
                    $userNotification->title = "การจองของคุณได้รับการยืนยัน";
                    $userNotification->details = $details;
                    $userNotification->is_read = false;
                    $userNotification->save();
                } else {
                    $userNotification = new UserNotification();
                    $userNotification->title = "การจองของคุณได้รับการยืนยัน";
                    $userNotification->details = ['count' => 1];
                    $userNotification->notification_type = NotificationType::RECRUIT_BOOKING_CONFIRM->value;
                    $userNotification->notificationable()->associate($recruitBooking->recruit);
                    $recruitBooking->author->notifications()->save($userNotification);
                }
            } else {

                $userNotification = UserNotification::whereHasMorph(
                    'notificationable',
                    [Recruit::class],
                    function ($query) use ($recruitBooking) {
                        $query->where('id', $recruitBooking->recruit->id);
                    }
                )
                    ->where('notification_type', NotificationType::RECRUIT_BOOKING_CONFIRM->value)
                    ->whereBelongsTo($recruitBooking->author, 'author')
                    ->first();
                if ($userNotification) {
                    $details = $userNotification->details;
                    $details['count'] = $details['count'] + 1;
                    $userNotification->details = $details;
                    $userNotification->is_read = false;
                    $userNotification->save();
                } else {
                    $userNotification = new UserNotification();
                    $userNotification->title = "ผู้รับงานยืนยันการจองของคุณ";
                    $userNotification->details = ['count' => 1];
                    $userNotification->notification_type = NotificationType::RECRUIT_BOOKING_CONFIRM->value;
                    $userNotification->notificationable()->associate($recruitBooking->recruit);
                    $recruitBooking->author->notifications()->save($userNotification);
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
     * Do Customer Confirm
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function doCustomerConfirm(Request $request, Int $recruitBookingId)
    {
        $post = ['customer_confirm_status' => ConfirmStatus::CONFIRM->value];
        $post['user_id'] = $request->user()->id;
        $post['recruit_booking_id'] = $recruitBookingId;

        $validatedRequest = Validator::make($post, [
            'recruit_booking_id' => 'required|exists:recruit_bookings,id',
            'user_id' => 'required',
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
            $recruitBooking = RecruitBooking::with('recruit')
                ->with('author')
                ->whereBelongsTo($user, 'author')
                ->find($recruitBookingId);

            if (!$recruitBooking) {
                return response()->json([
                    'status' => false,
                    'message' => 'no permission'
                ], 403);
            }

            $recruitBooking->customer_confirm_status = ConfirmStatus::CONFIRM->value;
            $recruitBooking->save();

            if ($recruitBooking->customer_confirm_status == $recruitBooking->worker_confirm_status) {
                $recruitBooking->booking_status = ConfirmStatus::CONFIRM->value;
                $recruitBooking->save();

                $userNotification = UserNotification::whereHasMorph(
                    'notificationable',
                    [Recruit::class],
                    function ($query) use ($recruitBooking) {
                        $query->where('id', $recruitBooking->recruit->id);
                    }
                )
                    ->where('notification_type', NotificationType::RECRUIT_BOOKING_CONFIRM->value)
                    ->whereBelongsTo($recruitBooking->author, 'author')
                    ->first();
                if ($userNotification) {
                    $details = $userNotification->details;
                    $details['count'] = $details['count'] + 1;
                    $userNotification->title = "การจองของลูกค้าได้รับการยืนยัน";
                    $userNotification->details = $details;
                    $userNotification->is_read = false;
                    $userNotification->save();
                } else {
                    $userNotification = new UserNotification();
                    $userNotification->title = "การจองของลูกค้าได้รับการยืนยัน";
                    $userNotification->details = ['count' => 1];
                    $userNotification->notification_type = NotificationType::RECRUIT_BOOKING_CONFIRM->value;
                    $userNotification->notificationable()->associate($recruitBooking->recruit);
                    $recruitBooking->author->notifications()->save($userNotification);
                }
            } else {

                $userNotification = UserNotification::whereHasMorph(
                    'notificationable',
                    [Recruit::class],
                    function ($query) use ($recruitBooking) {
                        $query->where('id', $recruitBooking->recruit->id);
                    }
                )
                    ->where('notification_type', NotificationType::RECRUIT_BOOKING_CONFIRM->value)
                    ->whereBelongsTo($recruitBooking->author, 'author')
                    ->first();
                if ($userNotification) {
                    $details = $userNotification->details;
                    $details['count'] = $details['count'] + 1;
                    $userNotification->details = $details;
                    $userNotification->is_read = false;
                    $userNotification->save();
                } else {
                    $userNotification = new UserNotification();
                    $userNotification->title = "ลูกค้ายืนยันการจองงาน";
                    $userNotification->details = ['count' => 1];
                    $userNotification->notification_type = NotificationType::RECRUIT_BOOKING_CONFIRM->value;
                    $userNotification->notificationable()->associate($recruitBooking->recruit);
                    $recruitBooking->author->notifications()->save($userNotification);
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
