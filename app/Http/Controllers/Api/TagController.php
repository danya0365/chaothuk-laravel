<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BannerProduct;
use App\Models\BannerPromotion;
use App\Models\Province;
use Carbon\Carbon;

/**
 * Class TagController
 * @package App\Http\Controllers
 */
class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $productTagQueryRows = BannerProduct::select('tags')->whereDate('expired_at', '>=', Carbon::now())->get();
        $promotionTagQueryRows = BannerPromotion::select('tags')->whereDate('expired_at', '>=', Carbon::now())->get();

        $allTags = [];

        foreach ($productTagQueryRows as $row) {
            $productTags = $row->tags;
            foreach ($productTags as $productTag) {
                if (!in_array($productTag, $allTags)) {
                    $allTags[] = $productTag;
                }
            }
        }
        foreach ($promotionTagQueryRows as $row) {
            $promotionTags = $row->tags;
            foreach ($promotionTags as $promotionTag) {
                if (!in_array($promotionTag, $allTags)) {
                    $allTags[] = $promotionTag;
                }
            }
        }

        return response()->json([
            'status' => true,
            'data' => self::toTagObjects($allTags),
        ], 200);
    }

    /**
     * Display a listing of the product resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function products()
    {
        $productTagQueryRows = BannerProduct::select('tags')->whereDate('expired_at', '>=', Carbon::now())->get();

        $allTags = [];

        foreach ($productTagQueryRows as $row) {
            $productTags = $row->tags;
            foreach ($productTags as $productTag) {
                if (!in_array($productTag, $allTags)) {
                    $allTags[] = $productTag;
                }
            }
        }

        return response()->json([
            'status' => true,
            'data' => self::toTagObjects($allTags),
        ], 200);
    }

    /**
     * Display a listing of the promotion resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function promotions()
    {
        $promotionTagQueryRows = BannerPromotion::select('tags')->whereDate('expired_at', '>=', Carbon::now())->get();

        $allTags = [];

        foreach ($promotionTagQueryRows as $row) {
            $promotionTags = $row->tags;
            foreach ($promotionTags as $promotionTag) {
                if (!in_array($promotionTag, $allTags)) {
                    $allTags[] = $promotionTag;
                }
            }
        }

        return response()->json([
            'status' => true,
            'data' => self::toTagObjects($allTags),
        ], 200);
    }

    private static function toTagObjects($tags)
    {
        $tagObjects = [];

        sort($tags);

        foreach ($tags as $key => $tag) {
            $tagObjects[] = ['id' => $key + 1, 'name' => ucfirst($tag), 'icon' => $tag, 'tag' => $tag];
        }
        return $tagObjects;
    }
}
