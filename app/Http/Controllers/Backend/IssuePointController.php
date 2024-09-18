<?php

namespace App\Http\Controllers\Backend;

use App\Enums\IssueType;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\IssuePointForStatusRequest;
use App\Models\IssuePoint;
use App\Http\Requests\IssuePointRequest;
use App\Http\Requests\UserMissionForStatusRequest;
use App\Models\IssuePointStatusLog;
use App\Models\PointTransactionLog;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserPointLog;
use App\Traits\SelectOption;
use Illuminate\Support\Facades\Auth;

/**
 * Class IssuePointController
 * @package App\Http\Controllers
 */
class IssuePointController extends Controller
{
    use SelectOption;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $issuePoints = IssuePoint::orderBy('id', 'desc')->paginate();

        return view('backend.issue-point.index', compact('issuePoints'))
            ->with('i', (request()->input('page', 1) - 1) * $issuePoints->perPage());
    }

    /**
     * Display a listing of the schedule resource.
     */
    public function scheduleList()
    {
        $issuePoints = IssuePoint::where('type', IssueType::REPEAT->value)->orderBy('id', 'desc')->paginate();

        return view('backend.issue-point.schedule-list', compact('issuePoints'))
            ->with('i', (request()->input('page', 1) - 1) * $issuePoints->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $issuePoint = new IssuePoint();
        $code = \Illuminate\Support\Str::random(8);
        $issuePoint->slug = strtoupper("SLUG-$code");
        $issuePointTypeSelections = $this->issueType();
        $cronRepeatTypeSelections = $this->cronRepeatType();
        $weekDaySelections = $this->weekDay();
        $dateSelections = $this->date();

        $customerId = request()->get('customerId', 0);
        $users = null;
        if (!$customerId)
            $users = User::query()->where('role_id', Role::CUSTOMER->value)->paginate()->withQueryString();

        $view = view('backend.issue-point.create', compact('issuePoint', 'customerId', 'users', 'issuePointTypeSelections', 'cronRepeatTypeSelections', 'weekDaySelections', 'dateSelections'));

        if ($users) {
            $view->with('i', (request()->input('page', 1) - 1) * $users->perPage());
        }
        return $view;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IssuePointRequest $request)
    {
        $post = $request->validated();
        $post['cron_info'] = $post;
        IssuePoint::create($post);

        return redirect()->route('backend.issue-points.index')
            ->with('success', 'IssuePoint created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $issuePoint = IssuePoint::find($id);

        return view('backend.issue-point.show', compact('issuePoint'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $issuePoint = IssuePoint::find($id);
        $issuePointTypeSelections = $this->issueType();
        return view('backend.issue-point.edit', compact('issuePoint', 'issuePointTypeSelections'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function showStatusForm($id)
    {
        $issuePoint = IssuePoint::find($id);
        $issueStatusSelections = $this->issueStatus();
        $issuePointTypeSelections = $this->issueType();

        return view('backend.issue-point.status-form', compact('issuePoint', 'issueStatusSelections', 'issuePointTypeSelections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IssuePointRequest $request, IssuePoint $issuePoint)
    {
        //$issuePoint->update($request->validated());

        return redirect()->route('backend.issue-points.index')
            ->with('error', 'IssuePoint was not updated');
    }

    /**
     * Update the specified resource in storage.
     */
    public function submitStatusForm(IssuePointForStatusRequest $request, $id)
    {
        if (!Auth::user()->backend?->is_can_approved) {
            return redirect()->back()
                ->with('error', 'IssuePoint was not updated');
        }

        $post = $request->validated();
        $issuePoint = IssuePoint::find($id);
        if ($issuePoint->isApproved()) {
            return redirect()->back()
                ->with('error', 'IssuePoint was not updated');
        }

        $issuePoint->update($post);

        if ($issuePoint->isApproved() && !$issuePoint->isTypeRepeat()) {
            $points = $issuePoint->points;
            $userId = $issuePoint->user_id;
            $userPoint = UserPoint::create([
                'point_received' => $points,
                'point_available' => $points,
                'user_id' => $userId,
                'issue_point_id' => $issuePoint->id,
                'expired_at' => null,
            ]);
            if ($userPoint) {
                $userPointLog = UserPointLog::create([
                    'points' => $points,
                    'user_point_id' => $userPoint->id,
                    'action_user_id' => $request->user()->id,
                ]);
                $pointTransactionLog = new PointTransactionLog();
                $pointTransactionLog->user_id = $userId;
                $pointTransactionLog->points = $points;
                $pointTransactionLog->user_point_logs = [$userPointLog];
                $pointTransactionLog->transactionable()->associate($issuePoint);
                $pointTransactionLog->save();
            }
        }

        $issuePointStatusLog = IssuePointStatusLog::create([
            'issue_point_status' => $issuePoint->status,
            'issue_point_id' => $issuePoint->id,
            'action_user_id' => $request->user()->id,
        ]);

        return redirect()->back()
            ->with('success', 'IssuePoint updated successfully');
    }

    public function destroy($id)
    {
        IssuePoint::find($id)->delete();

        return redirect()->route('backend.issue-points.index')
            ->with('success', 'IssuePoint deleted successfully');
    }
}
