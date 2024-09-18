<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerProductCollection;
use App\Http\Resources\BannerProductResource;
use App\Models\BannerProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class BannerProductController
 * @package App\Http\Controllers
 */
class BannerProductController extends Controller
{
    /**
     * Display a bannerProducts listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bannerProducts(Request $request)
    {
        $post = $request->all();
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => '$user not found',
            ], 403);
        }
        $customerUserTypes = $user->userTypes ?? [];
        $customerUserTypesId = [];
        foreach ($customerUserTypes as $customerUserType) {
            $customerUserTypesId[] = $customerUserType->id;
        }

        $query = BannerProduct::query();
        $customer = $user->customer;
        if ($customer) {
            $query->whereHas('userTypes', function ($query) use ($customerUserTypesId) {
                $query->whereIn('user_types.id', $customerUserTypesId);
            })
                ->whereDate('expired_at', '>=', Carbon::now());
        } else {
            $query->where('merchant_id', $user->id);
        }

        if (isset($post['tag'])) {
            $query->whereJsonContains('tags', $post['tag']);
        }

        $banners = $query->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new BannerProductCollection($banners),
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bannerProduct = BannerProduct::with('merchant')->find($id);

        return response()->json([
            'status' => $bannerProduct ? true : false,
            'data' => new BannerProductResource($bannerProduct),
        ], 200);
    }

    /**
     * Display the specified resource by code.
     */
    public function searchByCode($code)
    {
        $bannerProduct = BannerProduct::with('merchant')->where('code', $code)->first();

        return response()->json([
            'status' => $bannerProduct ? true : false,
            'data' => new BannerProductResource($bannerProduct),
        ], 200);
    }
}