<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserCustomerRequest;
use App\Models\IssuePoint;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\UserCouponLog;
use App\Models\UserCustomer;
use App\Models\UserMission;
use App\Models\UserPointLog;
use App\Traits\SelectOption;
use Illuminate\Support\Facades\Hash;

class UserCustomerController extends Controller
{
    use SelectOption;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::query()->where('role_id', Role::CUSTOMER->value)->orderBy('id', 'desc')->paginate()->withQueryString();

        return view('backend.user-customer.index', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * $users->perPage());
    }

    public function getCoupons($id)
    {
        $user = User::find($id);
        $coupons = UserCoupon::query()->with(['customer'])->whereBelongsTo($user->customer, 'customer')->orderBy('id', 'desc')->paginate()->withQueryString();

        return view('backend.user-customer.coupons', compact('user', 'coupons'))
            ->with('i', (request()->input('page', 1) - 1) * $coupons->perPage());
    }

    public function getMissions($id)
    {
        $user = User::find($id);
        $missions = UserMission::query()->with(['customer'])->whereBelongsTo($user->customer, 'customer')->orderBy('id', 'desc')->paginate()->withQueryString();

        return view('backend.user-customer.missions', compact('user', 'missions'))
            ->with('i', (request()->input('page', 1) - 1) * $missions->perPage());
    }

    public function getIssuePoints($id)
    {
        $user = User::find($id);
        $issuePoints = IssuePoint::query()->with(['customer'])->whereBelongsTo($user->customer, 'customer')->orderBy('id', 'desc')->paginate()->withQueryString();

        return view('backend.user-customer.issue-points', compact('user', 'issuePoints'))
            ->with('i', (request()->input('page', 1) - 1) * $issuePoints->perPage());
    }

    public function getPointLogs($id)
    {
        $user = User::find($id);
        $userId = $user->id;

        $post = request()->all();
        $startDateString = $post['start_date'] ?? null;
        $endDateString = $post['end_date'] ?? null;
        $startDate = $startDateString ? \Carbon\Carbon::parse($startDateString) : null;
        $endDate = $endDateString ? \Carbon\Carbon::parse($endDateString) : null;

        $query = UserPointLog::whereHas('userPoint', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        if ($startDate && $endDate)
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        $pointLogs = $query->orderBy('id', 'desc')->paginate()->withQueryString();

        return view('backend.user-customer.point-logs', compact('user', 'pointLogs'))
            ->with('i', (request()->input('page', 1) - 1) * $pointLogs->perPage());
    }

    public function getCouponLogs($id)
    {
        $user = User::find($id);
        $userId = $user->id;

        $post = request()->all();
        $startDateString = $post['start_date'] ?? null;
        $endDateString = $post['end_date'] ?? null;
        $startDate = $startDateString ? \Carbon\Carbon::parse($startDateString) : null;
        $endDate = $endDateString ? \Carbon\Carbon::parse($endDateString) : null;

        $query = UserCouponLog::whereHas('userCoupon', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        if ($startDate && $endDate)
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        $couponLogs = $query->orderBy('id', 'desc')->paginate()->withQueryString();

        return view('backend.user-customer.coupon-logs', compact('user', 'couponLogs'))
            ->with('i', (request()->input('page', 1) - 1) * $couponLogs->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        $personTypeSelections = $this->personType();
        $genderSelections = $this->gender();
        $userTypeSelections = $this->userType();
        return view('backend.user-customer.create', compact('user', 'personTypeSelections', 'genderSelections', 'userTypeSelections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCustomerRequest $request)
    {
        $post = $request->validated();
        $post['person_info'] = $post;
        $post['password'] = Hash::make($post['password']);
        $post['role_id'] = Role::CUSTOMER->value;
        $user = User::create($post);
        $user->customer()->create($post);
        $user->syncUserTypes($post['user_types']);
        return redirect()->route('backend.user-customers.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('backend.user-customer.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $personTypeSelections = $this->personType();
        $genderSelections = $this->gender();
        $userTypeSelections = $this->userType();
        return view('backend.user-customer.edit', compact('user', 'personTypeSelections', 'genderSelections', 'userTypeSelections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserCustomerRequest $request, UserCustomer $userCustomer)
    {
        $post = $request->validated();
        //$post = $request->all();
        $post['person_info'] = $post;
        $userId = $post['user_id'];
        $user = User::query()->find($userId);
        if (trim($post['password'])) {
            $post['password'] = Hash::make(trim($post['password']));
        } else {
            unset($post['password']);
        }
        $user->update($post);
        $user->syncUserTypes($post['user_types']);

        $userCustomer = UserCustomer::query()->find($userId);
        if (!$userCustomer) {
            $userCustomer = UserCustomer::create(['user_id' => $userId, ...$post]);
        } else {
            $userCustomer->update($post);
        }

        return redirect()->route('backend.user-customers.index')
            ->with('success', 'User updated successfully');
    }
}
