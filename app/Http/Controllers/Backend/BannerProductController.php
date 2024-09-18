<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerProductRequest;
use App\Models\BannerProduct;
use App\Models\User;
use App\Traits\SelectOption;

/**
 * Class BannerProductController
 * @package App\Http\Controllers
 */
class BannerProductController extends Controller
{
    use SelectOption;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bannerProducts = BannerProduct::orderBy('id', 'desc')->paginate();

        return view('backend.banner-product.index', compact('bannerProducts'))
            ->with('i', (request()->input('page', 1) - 1) * $bannerProducts->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bannerProduct = new BannerProduct();
        $couponExpiresTypeSelections = $this->couponExpiresType();
        $userTypeSelections = $this->userType();
        $merchantId = request()->get('merchant_id', 0);
        $users = User::query()->where('role_id', Role::MERCHANT->value)->paginate()->withQueryString();

        return view('backend.banner-product.create', compact('users', 'bannerProduct', 'couponExpiresTypeSelections', 'userTypeSelections', 'merchantId'))
            ->with('i', (request()->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BannerProductRequest $request)
    {
        $post = $request->validated();

        $tags = explode(',', $post['tags']);
        $trimTags = array_map('trim', $tags);
        $post['tags'] = $trimTags;

        $code = \Illuminate\Support\Str::random(8);
        $post['code'] = $code;
        $bannerProduct = BannerProduct::create($post);
        $bannerProduct->syncUserType($post['user_types']);
        $bannerProduct->code = sprintf('%s%s%04d', BannerProduct::$codePrefix, date('ymd'), $bannerProduct->id);
        $bannerProduct->save();

        return redirect()->route('backend.banner-products.index')
            ->with('success', 'BannerProduct created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bannerProduct = BannerProduct::find($id);

        return view('backend.banner-product.show', compact('bannerProduct'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bannerProduct = BannerProduct::find($id);
        $couponExpiresTypeSelections = $this->couponExpiresType();
        $userTypeSelections = $this->userType();
        $merchantId = $bannerProduct->merchant_id;
        return view('backend.banner-product.edit', compact('bannerProduct', 'couponExpiresTypeSelections', 'userTypeSelections', 'merchantId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BannerProductRequest $request, BannerProduct $bannerProduct)
    {
        $post = $request->validated();

        $tags = explode(',', $post['tags']);
        $trimTags = array_map('trim', $tags);
        $post['tags'] = $trimTags;
        $bannerProduct->update($post);

        $bannerProduct->syncUserType($post['user_types']);
        return redirect()->route('backend.banner-products.index')
            ->with('success', 'BannerProduct updated successfully');
    }

    public function destroy($id)
    {
        BannerProduct::find($id)->delete();

        return redirect()->route('backend.banner-products.index')
            ->with('success', 'BannerProduct deleted successfully');
    }
}
