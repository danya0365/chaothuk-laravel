<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Requests\BannerRequest;
use App\Traits\SelectOption;

/**
 * Class BannerController
 * @package App\Http\Controllers
 */
class BannerController extends Controller
{
    use SelectOption;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderBy('id', 'desc')->paginate();

        return view('backend.banner.index', compact('banners'))
            ->with('i', (request()->input('page', 1) - 1) * $banners->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bannerType = request()->get('type', 0);
        $merchantId = request()->get('merchantId', 0);

        $banner = new Banner();
        $bannerTypeSelections = $this->bannerType();
        $publicSelections = $this->yesNo();
        return view('backend.banner.create', compact('banner', 'bannerTypeSelections', 'publicSelections', 'bannerProductSelections', 'bannerPromotionSelections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BannerRequest $request)
    {
        $post = $request->validated();
        Banner::create($post);

        return redirect()->route('backend.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $banner = Banner::find($id);

        return view('backend.banner.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $banner = Banner::find($id);
        $bannerTypeSelections = $this->bannerType();
        $publicSelections = $this->yesNo();
        return view('backend.banner.edit', compact('banner', 'bannerTypeSelections', 'publicSelections', 'bannerProductSelections', 'bannerPromotionSelections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BannerRequest $request, Banner $banner)
    {
        $post = $request->validated();
        $banner->update($post);

        return redirect()->route('backend.banners.index')
            ->with('success', 'Banner updated successfully');
    }

    public function destroy($id)
    {
        Banner::find($id)->delete();

        return redirect()->route('backend.banners.index')
            ->with('success', 'Banner deleted successfully');
    }
}