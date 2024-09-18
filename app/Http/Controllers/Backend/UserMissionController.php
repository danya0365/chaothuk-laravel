<?php

namespace App\Http\Controllers\Backend;

use App\Enums\IssueStatus;
use App\Enums\IssueType;
use App\Enums\MissionStatus;
use App\Enums\PromotionType;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserMissionForStatusRequest;
use App\Models\UserMission;
use App\Http\Requests\UserMissionRequest;
use App\Models\BannerPromotion;
use App\Models\IssuePoint;
use App\Models\IssuePointStatusLog;
use App\Models\PointTransactionLog;
use App\Models\User;
use App\Models\UserMissionStatusLog;
use App\Models\UserPoint;
use App\Models\UserPointLog;
use App\Traits\SelectOption;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserMissionController
 * @package App\Http\Controllers
 */
class UserMissionController extends Controller
{
    use SelectOption;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userMissions = UserMission::orderBy('id', 'desc')->paginate();

        return view('backend.user-mission.index', compact('userMissions'))
            ->with('i', (request()->input('page', 1) - 1) * $userMissions->perPage());
    }

    public function getInProgressList()
    {
        $userMissions = UserMission::query()->where('user_missions.status', MissionStatus::IN_PROGRESS->value)->orderBy('id', 'desc')->paginate()->withQueryString();

        return view('backend.user-mission.in-progress-list', compact('userMissions'))
            ->with('i', (request()->input('page', 1) - 1) * $userMissions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userMission = new UserMission();
        $missionStatusSelections = $this->missionStatus();
        $customerId = request()->get('customerId', 0);
        $users = null;
        if (!$customerId)
            $users = User::query()->where('role_id', Role::CUSTOMER->value)->paginate()->withQueryString();

        $bannerPromotionId = request()->get('bannerPromotionId', 0);
        $bannerPromotions = null;
        if (!$bannerPromotionId)
            $bannerPromotions = BannerPromotion::query()->where('type', PromotionType::MISSION->value)->paginate()->withQueryString();

        $view = view('backend.user-mission.create', compact('userMission', 'customerId', 'users', 'bannerPromotionId', 'bannerPromotions', 'missionStatusSelections'));

        if ($users) {
            $view->with('i', (request()->input('page', 1) - 1) * $users->perPage());
        }
        if ($bannerPromotions) {
            $view->with('i', (request()->input('page', 1) - 1) * $bannerPromotions->perPage());
        }
        return $view;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserMissionRequest $request)
    {
        UserMission::create($request->validated());

        return redirect()->route('backend.user-missions.index')
            ->with('success', 'UserMission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $userMission = UserMission::find($id);

        return view('backend.user-mission.show', compact('userMission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $userMission = UserMission::find($id);
        $missionStatusSelections = $this->missionStatus();
        $customerId = $userMission->user_id;
        $bannerPromotionId = $userMission->banner_promotion_id;

        return view('backend.user-mission.edit', compact('userMission', 'customerId', 'bannerPromotionId', 'missionStatusSelections'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function showStatusForm($id)
    {
        $userMission = UserMission::find($id);
        $missionStatusSelections = $this->missionStatus();
        $customerId = $userMission->user_id;
        $bannerPromotionId = $userMission->banner_promotion_id;

        return view('backend.user-mission.status-form', compact('userMission', 'customerId', 'bannerPromotionId', 'missionStatusSelections'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UserMissionRequest $request, UserMission $userMission)
    {
        //$userMission->update($request->validated());

        return redirect()->route('backend.user-missions.index')
            ->with('error', 'UserMission was not updated');
    }

    /**
     * Update the specified resource in storage.
     */
    public function submitStatusForm(UserMissionForStatusRequest $request, $id)
    {
        if (!Auth::user()->backend?->is_can_approved) {
            return redirect()->back()
                ->with('error', 'UserMission was not updated');
        }

        $post = $request->validated();
        $userMission = UserMission::find($id);
        if ($userMission->isComplete()) {
            return redirect()->back()
                ->with('error', 'UserMission was not updated');
        }

        $userMission->update($post);

        if ($userMission->isComplete()) {
            $slug = 'mission';
            $name = 'mission';
            $desc = 'mission';
            $points = $userMission->points;
            $type = IssueType::ONE_TIME->value;
            $status = IssueStatus::APPROVE->value;
            $userId = $userMission->user_id;
            $issuePoint = IssuePoint::create([
                'slug' => $slug,
                'name' => $name,
                'desc' => $desc,
                'points' => $points,
                'type' => $type,
                'status' => $status,
                'user_id' => $userId,
                'user_mission_id' => $userMission->id
            ]);
            if ($issuePoint) {
                $issuePointStatusLog = IssuePointStatusLog::create([
                    'issue_point_status' => $issuePoint->status,
                    'issue_point_id' => $issuePoint->id,
                    'action_user_id' => $request->user()->id,
                ]);
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
                    $pointTransactionLog->transactionable()->associate($userMission);
                    $pointTransactionLog->save();
                    //$userCustomer->transactions()->save($pointTransactionLog);
                }
            }
        }

        UserMissionStatusLog::create([
            'user_mission_status' => $userMission->status,
            'user_mission_id' => $userMission->id,
            'action_user_id' => $request->user()->id,
        ]);

        return redirect()->back()
            ->with('success', 'UserMission updated successfully');
    }

    public function destroy($id)
    {
        UserMission::find($id)->delete();

        return redirect()->route('backend.user-missions.index')
            ->with('success', 'UserMission deleted successfully');
    }
}
