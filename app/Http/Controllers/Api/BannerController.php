<?php

namespace App\Http\Controllers\Api;

use App\Enums\BannerType;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerCollection;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;

/**
 * Class BannerController
 * @package App\Http\Controllers
 */
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $banners = Banner::query()->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new BannerCollection($banners),
        ], 200);
    }

    /**
     * Display a pinned listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pinnedBanners()
    {
        $banners = Banner::where('is_pinned', 1)->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new BannerCollection($banners),
        ], 200);
    }

    /**
     * Display a bannerProducts listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bannerProducts(Request $request)
    {
        $customer = $request->user();
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => '$customer not found',
            ], 403);
        }
        $customerUserTypes = $customer->userTypes ?? [];
        $customerUserTypesId = [];
        foreach ($customerUserTypes as $customerUserType) {
            $customerUserTypesId[] = $customerUserType->id;
        }

        $banners = Banner::with('bannerProduct')
            ->where('type', BannerType::PRODUCT->value)
            ->whereHas('bannerProduct', function ($query) use ($customerUserTypesId) {
                $query->whereHas('userTypes', function ($query) use ($customerUserTypesId) {
                    $query->whereIn('user_types.id', $customerUserTypesId);
                });
            })
            ->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new BannerCollection($banners),
        ], 200);
    }

    /**
     * Display a bannerPromotions listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bannerPromotions(Request $request)
    {
        $customer = $request->user();
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => '$customer not found',
            ], 403);
        }
        $customerUserTypes = $customer->userTypes ?? [];
        $customerUserTypesId = [];
        foreach ($customerUserTypes as $customerUserType) {
            $customerUserTypesId[] = $customerUserType->id;
        }

        $banners = Banner::with('bannerPromotion')
            ->where('type', BannerType::PROMOTION->value)
            ->whereHas('bannerPromotion', function ($query) use ($customerUserTypesId) {
                $query->whereHas('userTypes', function ($query) use ($customerUserTypesId) {
                    $query->whereIn('user_types.id', $customerUserTypesId);
                });
            })
            ->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new BannerCollection($banners),
        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $banner = Banner::with(['bannerProduct' => function ($q) {
            return $q->with('merchant');
        }])->with(['bannerPromotion' => function ($q) {
            return $q->with('merchant');
        }])->where('id', $id)->first();

        if ($banner) {
            $banner->view_count += 1;
            $banner->save();
        }

        return response()->json([
            'status' => $banner ? true : false,
            'data' => new BannerResource($banner),
        ], 200);
    }

    /**
     * Display lastUpdate.
     *
     * @return \Illuminate\Http\Response
     */
    public function lastUpdate()
    {
        $banner = Banner::orderBy('updated_at', 'desc')->take(1)->first();

        return response()->json([
            'status' => $banner ? true : false,
            'data' => $banner ? new BannerResource($banner) : null,
        ], 200);
    }
}