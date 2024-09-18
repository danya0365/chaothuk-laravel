<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerPromotionRequest;
use App\Models\BannerPromotion;
use App\Models\User;
use App\Traits\SelectOption;

/**
 * Class BannerPromotionController
 * @package App\Http\Controllers
 */
class BannerPromotionController extends Controller
{
    use SelectOption;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bannerPromotions = BannerPromotion::orderBy('id', 'desc')->paginate();

        return view('backend.banner-promotion.index', compact('bannerPromotions'))
            ->with('i', (request()->input('page', 1) - 1) * $bannerPromotions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bannerPromotion = new BannerPromotion();
        $promotionTypeSelections = $this->promotionType();
        $userTypeSelections = $this->userType();
        $merchantId = request()->get('merchant_id', 0);
        $users = User::query()->where('role_id', Role::MERCHANT->value)->paginate()->withQueryString();
        return view('backend.banner-promotion.create', compact('users', 'bannerPromotion', 'promotionTypeSelections', 'userTypeSelections', 'merchantId'))
            ->with('i', (request()->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BannerPromotionRequest $request)
    {
        $post = $request->validated();

        $tags = explode(',', $post['tags']);
        $trimTags = array_map('trim', $tags);
        $post['tags'] = $trimTags;
        $code = \Illuminate\Support\Str::random(8);
        $post['code'] = $code;
        $bannerPromotion = BannerPromotion::create($post);
        if (isset($post['user_types']))
            $bannerPromotion->syncUserType($post['user_types']);

        $bannerPromotion->code = sprintf('%s%s%04d', BannerPromotion::$codePrefix, date('ymd'), $bannerPromotion->id);
        $bannerPromotion->save();

        return redirect()->route('backend.banner-promotions.index')
            ->with('success', 'BannerPromotion created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bannerPromotion = BannerPromotion::find($id);

        return view('backend.banner-promotion.show', compact('bannerPromotion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bannerPromotion = BannerPromotion::find($id);
        $promotionTypeSelections = $this->promotionType();
        $userTypeSelections = $this->userType();
        $merchantId = $bannerPromotion->merchant_id;
        return view('backend.banner-promotion.edit', compact('bannerPromotion', 'promotionTypeSelections', 'userTypeSelections', 'merchantId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BannerPromotionRequest $request, BannerPromotion $bannerPromotion)
    {
        $post = $request->validated();

        $tags = explode(',', $post['tags']);
        $trimTags = array_map('trim', $tags);
        $post['tags'] = $trimTags;

        $bannerPromotion->update($post);
        if (isset($post['user_types']))
            $bannerPromotion->syncUserType($post['user_types']);
        return redirect()->route('backend.banner-promotions.index')
            ->with('success', 'BannerPromotion updated successfully');
    }

    public function destroy($id)
    {
        BannerPromotion::find($id)->delete();

        return redirect()->route('backend.banner-promotions.index')
            ->with('success', 'BannerPromotion deleted successfully');
    }
}
