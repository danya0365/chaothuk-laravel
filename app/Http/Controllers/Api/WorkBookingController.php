<?php

namespace App\Http\Controllers\Api;

use App\Enums\ConfirmStatus;
use App\Enums\NotificationType;
use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Models\Work;
use App\Models\WorkBooking;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkBookingController extends Controller
{

    /**
     * Do Worker Confirm
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function doWorkerConfirm(Request $request, Int $workBookingId)
    {
        $post = ['worker_confirm_status' => ConfirmStatus::Confirm()];
        $post['user_id'] = $request->user()->id;
        $post['work_booking_id'] = $workBookingId;

        $validatedRequest = Validator::make($post,  [
            'work_booking_id' => 'required|exists:work_bookings,id',
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
            $workBooking = WorkBooking::with('work')->with('author')->whereHas('work', function ($query) use ($user) {
                $query->with('author')->whereBelongsTo($user, 'author');
            })->find($workBookingId);

            if (!$workBooking) {
                return response()->json([
                    'status' => false,
                    'message' => 'no permission'
                ], 403);
            }

            $workBooking->worker_confirm_status = ConfirmStatus::Confirm();
            $workBooking->save();

            if ($workBooking->worker_confirm_status == $workBooking->customer_confirm_status) {
                $workBooking->booking_status = ConfirmStatus::Confirm();
                $workBooking->save();


                $userNotification = UserNotification::whereHasMorph(
                    'notificationable',
                    [Work::class],
                    function ($query) use ($workBooking) {
                        $query->where('id', $workBooking->work->id);
                    }
                )
                    ->where('notification_type', NotificationType::BookingConfirm())
                    ->whereBelongsTo($workBooking->author, 'author')
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
                    $userNotification->notification_type = NotificationType::BookingConfirm();
                    $userNotification->notificationable()->associate($workBooking->work);
                    $workBooking->author->notifications()->save($userNotification);
                }
            } else {

                $userNotification = UserNotification::whereHasMorph(
                    'notificationable',
                    [Work::class],
                    function ($query) use ($workBooking) {
                        $query->where('id', $workBooking->work->id);
                    }
                )
                    ->where('notification_type', NotificationType::BookingConfirm())
                    ->whereBelongsTo($workBooking->author, 'author')
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
                    $userNotification->notification_type = NotificationType::BookingConfirm();
                    $userNotification->notificationable()->associate($workBooking->work);
                    $workBooking->author->notifications()->save($userNotification);
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
    public function doCustomerConfirm(Request $request, Int $workBookingId)
    {
        $post = ['customer_confirm_status' => ConfirmStatus::Confirm()];
        $post['user_id'] = $request->user()->id;
        $post['work_booking_id'] = $workBookingId;

        $validatedRequest = Validator::make($post,  [
            'work_booking_id' => 'required|exists:work_bookings,id',
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
            $workBooking = WorkBooking::with('work')
                ->with('author')
                ->whereBelongsTo($user, 'author')
                ->find($workBookingId);

            if (!$workBooking) {
                return response()->json([
                    'status' => false,
                    'message' => 'no permission'
                ], 403);
            }

            $workBooking->customer_confirm_status = ConfirmStatus::Confirm();
            $workBooking->save();

            if ($workBooking->customer_confirm_status == $workBooking->customer_confirm_status) {
                $workBooking->booking_status = ConfirmStatus::Confirm();
                $workBooking->save();

                $userNotification = UserNotification::whereHasMorph(
                    'notificationable',
                    [Work::class],
                    function ($query) use ($workBooking) {
                        $query->where('id', $workBooking->work->id);
                    }
                )
                    ->where('notification_type', NotificationType::BookingConfirm())
                    ->whereBelongsTo($workBooking->author, 'author')
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
                    $userNotification->notification_type = NotificationType::BookingConfirm();
                    $userNotification->notificationable()->associate($workBooking->work);
                    $workBooking->author->notifications()->save($userNotification);
                }
            } else {

                $userNotification = UserNotification::whereHasMorph(
                    'notificationable',
                    [Work::class],
                    function ($query) use ($workBooking) {
                        $query->where('id', $workBooking->work->id);
                    }
                )
                    ->where('notification_type', NotificationType::BookingConfirm())
                    ->whereBelongsTo($workBooking->author, 'author')
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
                    $userNotification->notification_type = NotificationType::BookingConfirm();
                    $userNotification->notificationable()->associate($workBooking->work);
                    $workBooking->author->notifications()->save($userNotification);
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
