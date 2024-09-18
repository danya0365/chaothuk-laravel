<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Enums\Role;
use App\Models\UserCoupon;
use App\Http\Requests\UserCouponRequest;
use App\Models\BannerProduct;
use App\Models\User;

/**
 * Class UserCouponController
 * @package App\Http\Controllers
 */
class UserCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $couponCode = request()->get('coupon_code', '');
        $query = UserCoupon::query();
        if ($couponCode) {
            $query->where('code', 'like', '%' . $couponCode . '%');
        }

        $userCoupons = $query->orderBy('id', 'desc')->paginate();

        return view('backend.user-coupon.index', compact('userCoupons'))
            ->with('i', (request()->input('page', 1) - 1) * $userCoupons->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userCoupon = new UserCoupon();
        $customerId = request()->get('customerId', 0);
        $users = null;
        if (!$customerId)
            $users = User::query()->where('role_id', Role::CUSTOMER->value)->paginate()->withQueryString();

        $bannerProductId = request()->get('bannerProductId', 0);
        $bannerProducts = null;
        if (!$bannerProductId)
            $bannerProducts = BannerProduct::query()->paginate()->withQueryString();

        $view = view('backend.user-coupon.create', compact('userCoupon', 'customerId', 'users', 'bannerProductId', 'bannerProducts'));

        if ($users) {
            $view->with('i', (request()->input('page', 1) - 1) * $users->perPage());
        }
        if ($bannerProducts) {
            $view->with('i', (request()->input('page', 1) - 1) * $bannerProducts->perPage());
        }
        return $view;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCouponRequest $request)
    {
        $post = $request->validated();
        $code = \Illuminate\Support\Str::random(8);
        $post['code'] = $code;
        $userCoupon = UserCoupon::create($post);

        $userCoupon->code = sprintf('%s%s%06d', UserCoupon::$codePrefix, date('ymd'), $userCoupon->id);
        $userCoupon->save();

        return redirect()->route('backend.user-coupons.index')
            ->with('success', 'UserCoupon created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $userCoupon = UserCoupon::find($id);

        return view('backend.user-coupon.show', compact('userCoupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $userCoupon = UserCoupon::find($id);
        $customerId = $userCoupon->user_id;
        $bannerProductId = $userCoupon->banner_product_id;

        return view('backend.user-coupon.edit', compact('userCoupon', 'customerId', 'bannerProductId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserCouponRequest $request, UserCoupon $userCoupon)
    {
        $post = $request->validated();
        $userCoupon->update($post);

        return redirect()->route('backend.user-coupons.index')
            ->with('success', 'UserCoupon updated successfully');
    }

    public function destroy($id)
    {
        UserCoupon::find($id)->delete();

        return redirect()->route('backend.user-coupons.index')
            ->with('success', 'UserCoupon deleted successfully');
    }
}
