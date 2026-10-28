<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\WorkAvailabilityResource;
use App\Http\Resources\Api\V1\WorkBlockedDateResource;
use App\Models\Work;
use App\Models\WorkAvailability;
use App\Models\WorkBlockedDate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * A work's weekly schedule and one-off blocked dates.
 *
 * The web form is owner-eyes only and so is this API: every route resolves the
 * work, then pins it to the caller through author_id before reading or writing
 * — a guessed work id simply matches nothing.
 */
class AvailabilityController extends Controller
{
    /**
     * The whole schedule — seven weekday slots plus the upcoming blocked dates,
     * the same two lists the web form renders.
     */
    public function show(Request $request, Work $work): JsonResponse
    {
        $this->callerOwns($request, $work);

        $availability = WorkAvailability::query()->where('work_id', $work->id)->get();

        $schedule = collect(range(0, 6))->map(function (int $day) use ($availability) {
            $slot = $availability->firstWhere('day_of_week', $day);

            return [
                'day_of_week' => $day,
                // A slot that was never configured still renders on the form,
                // as the web's dayNames list does.
                'is_available' => $slot !== null && (bool) $slot->is_available,
                'start_time' => $slot?->start_time ? substr((string) $slot->start_time, 0, 5) : '08:00',
                'end_time' => $slot?->end_time ? substr((string) $slot->end_time, 0, 5) : '17:00',
            ];
        })->values();

        $blockedDates = WorkBlockedDate::query()
            ->where('work_id', $work->id)
            ->whereDate('blocked_date', '>=', now()->toDateString())
            ->orderBy('blocked_date')
            ->get();

        return response()->json([
            'data' => [
                'work_id' => $work->id,
                'schedule' => $schedule,
                'blocked_dates' => WorkBlockedDateResource::collection($blockedDates),
            ],
        ]);
    }

    /**
     * Replace the weekly schedule in one shot.
     *
     * The client owns the full week it submitted: rows it stopped sending are
     * deleted, rows that changed are updated, new days are created — so one
     * request mirrors the seven toggles and edits of the web form.
     */
    public function update(Request $request, Work $work): JsonResponse
    {
        $this->callerOwns($request, $work);

        $validated = $request->validate([
            'schedule' => ['required', 'array', 'max:7'],
            'schedule.*.day_of_week' => ['required', 'integer', 'between:0,6'],
            'schedule.*.start_time' => ['required', 'date_format:H:i'],
            'schedule.*.end_time' => ['required', 'date_format:H:i'],
            'schedule.*.is_available' => ['required', 'boolean'],
        ]);

        $submitted = collect($validated['schedule'])->keyBy('day_of_week');

        // Only actually-listed days stay; days absent from the payload are gone.
        WorkAvailability::query()
            ->where('work_id', $work->id)
            ->whereNotIn('day_of_week', $submitted->keys()->all())
            ->delete();

        foreach ($submitted as $day) {
            WorkAvailability::query()->updateOrCreate(
                [
                    'work_id' => $work->id,
                    'day_of_week' => $day['day_of_week'],
                ],
                [
                    'start_time' => $day['start_time'],
                    'end_time' => $day['end_time'],
                    'is_available' => $day['is_available'],
                ],
            );
        }

        $availability = WorkAvailability::query()->where('work_id', $work->id)->get();

        return response()->json([
            'data' => [
                'work_id' => $work->id,
                'schedule' => WorkAvailabilityResource::collection($availability),
            ],
        ]);
    }

    /**
     * Block a single date for this work — firstOrCreate like the web form, so a
     * re-submitted date keeps its original reason instead of erroring.
     *
     * The match is whereDate: the model's date cast stores the column with a
     * time part on some drivers, while the client speaks plain Y-m-d.
     */
    public function storeBlockedDate(Request $request, Work $work): JsonResponse
    {
        $this->callerOwns($request, $work);

        $validated = $request->validate([
            'blocked_date' => ['required', 'date', 'after_or_equal:today'],
            'reason' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $blocked = WorkBlockedDate::query()
            ->where('work_id', $work->id)
            ->whereDate('blocked_date', $validated['blocked_date'])
            ->first();

        $created = ! $blocked;

        if (! $blocked) {
            $blocked = WorkBlockedDate::create([
                'work_id' => $work->id,
                'blocked_date' => $validated['blocked_date'],
                'reason' => $validated['reason'] ?? null,
            ]);
        }

        // First time is 201 Created; re-submitting an existing date reuses the
        // row, so the original reason survives and the answer is 200.
        return (new WorkBlockedDateResource($blocked))
            ->response()
            ->setStatusCode($created ? 201 : 200);
    }

    /**
     * Unblock one of the work's dates, scoped to the owner.
     */
    public function destroyBlockedDate(Request $request, Work $work, int $blockedDate): Response
    {
        $this->callerOwns($request, $work);

        WorkBlockedDate::query()
            ->where('work_id', $work->id)
            ->whereKey($blockedDate)
            ->delete();

        return response()->noContent();
    }

    /**
     * The work must belong to the caller.
     */
    private function callerOwns(Request $request, Work $work): void
    {
        if ($work->author_id !== $request->user()->id) {
            throw new AccessDeniedHttpException('คุณจัดการตารางว่างได้เฉพาะประกาศของตัวเอง');
        }
    }
}
