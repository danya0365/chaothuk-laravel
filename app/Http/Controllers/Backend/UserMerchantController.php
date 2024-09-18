<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserMerchantRequest;
use App\Models\BannerProduct;
use App\Models\BannerPromotion;
use App\Models\User;
use App\Models\UserMerchant;
use App\Traits\SelectOption;
use Illuminate\Support\Facades\Hash;

class UserMerchantController extends Controller
{
    use SelectOption;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::query()->where('role_id', Role::MERCHANT->value)->paginate()->withQueryString();

        return view('backend.user-merchant.index', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * $users->perPage());
    }

    public function getBannerProducts($id)
    {
        $user = User::find($id);
        $bannerProducts = BannerProduct::query()->with(['merchant'])->whereBelongsTo($user->merchant, 'merchant')->paginate()->withQueryString();

        return view('backend.user-merchant.banner-products', compact('user', 'bannerProducts'))
            ->with('i', (request()->input('page', 1) - 1) * $bannerProducts->perPage());
    }

    public function getBannerPromotions($id)
    {
        $user = User::find($id);
        $bannerPromotions = BannerPromotion::query()->with(['merchant'])->whereBelongsTo($user->merchant, 'merchant')->paginate()->withQueryString();

        return view('backend.user-merchant.banner-promotions', compact('user', 'bannerPromotions'))
            ->with('i', (request()->input('page', 1) - 1) * $bannerPromotions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        $genderSelections = $this->gender();
        return view('backend.user-merchant.create', compact('user', 'genderSelections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserMerchantRequest $request)
    {
        $post = $request->validated();
        $post['person_info'] = $post;
        $post['password'] = Hash::make($post['password']);
        $post['role_id'] = Role::MERCHANT->value;
        $user = User::create($post);
        $user->merchant()->create([...$post, 'name' => $post['shop_name']]);
        return redirect()->route('backend.user-merchants.index')
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
        return view('backend.user-merchant.show', compact('user'));
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
        return view('backend.user-merchant.edit', compact('user', 'personTypeSelections', 'genderSelections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserMerchantRequest $request, UserMerchant $userMerchant)
    {
        $post = $request->validated();

        $post['person_info'] = $post;
        $userId = $post['user_id'];
        $user = User::query()->find($userId);
        if (trim($post['password'])) {
            $post['password'] = Hash::make(trim($post['password']));
        } else {
            unset($post['password']);
        }
        $user->update($post);

        $userMerchant = UserMerchant::query()->find($userId);
        if (!$userMerchant) {
            $userMerchant = UserMerchant::create(['user_id' => $userId, ...$post, 'name' => $post['shop_name']]);
        } else {
            $userMerchant->update([...$post, 'name' => $post['shop_name']]);
        }

        return redirect()->route('backend.user-merchants.index')
            ->with('success', 'User updated successfully');
    }
}
