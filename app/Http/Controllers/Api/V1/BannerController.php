<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BannerResource;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;

/**
 * The home screen's banner strip.
 *
 * The web front page fills its strip from the same five public, unexpired
 * banners — pinned first, newest next — so the API serves exactly that cut.
 */
class BannerController extends Controller
{
    /**
     * The strip length the web front page lives with.
     */
    public const STRIP_LENGTH = 5;

    public function index(): JsonResponse
    {
        $banners = Banner::query()
            ->where('is_public', true)
            ->where(function ($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->limit(self::STRIP_LENGTH)
            ->get();

        return response()->json([
            'data' => BannerResource::collection($banners),
        ]);
    }
}
