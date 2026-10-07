<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Permission;
use App\Enums\WorkStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreWorkRequest;
use App\Http\Resources\Api\V1\WorkCollection;
use App\Http\Resources\Api\V1\WorkResource;
use App\Models\Work;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Works — "I can do this job".
 */
class WorkController extends Controller
{
    /**
     * The relations every card needs, so the list endpoint stays at one query per
     * relation instead of lazy-loading per row.
     *
     * @return array<int, string>
     */
    private const CARD_RELATIONS = ['province', 'workType', 'author'];

    public function index(Request $request): WorkCollection
    {
        $query = Work::query()->with(self::CARD_RELATIONS);

        $this->applyFilters($query, $request);

        $works = $query
            ->orderBy('display_priority', 'desc')
            ->orderByDesc('created_at')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return WorkCollection::make($works);
    }

    public function show(Work $work): WorkResource
    {
        $work->load(['province', 'workType', 'author', 'categories']);

        return new WorkResource($work);
    }

    /**
     * @throws AccessDeniedHttpException when the account may not post works
     */
    public function store(StoreWorkRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->isPermission(Permission::CREATE_WORK->value)) {
            throw new AccessDeniedHttpException('คุณยังไม่มีสิทธิ์ลงประกาศงาน');
        }

        $post = $request->validated();

        // author_id comes from the token, never from the request body.
        $work = Work::create([
            'code' => $post['code'],
            'title' => $post['title'],
            'description' => $post['description'],
            'details' => $post['details'] ?? null,
            'price' => $post['price'],
            'primary_image' => $post['primary_image'] ?? null,
            'images' => $post['images'] ?? null,
            'province_id' => $post['province_id'],
            'work_type_id' => $post['work_type_id'],
            'latitude' => $post['latitude'] ?? null,
            'longitude' => $post['longitude'] ?? null,
            'work_status' => WorkStatus::STAND_BY->value,
            'author_id' => $user->id,
        ]);

        if (! empty($post['categories'])) {
            $work->categories()->sync($post['categories']);
        }

        $work->load(self::CARD_RELATIONS);

        return (new WorkResource($work))->response()->setStatusCode(201);
    }

    /**
     * @param  Builder<Work>  $query
     */
    private function applyFilters(Builder $query, Request $request): void
    {
        if ($keyword = trim((string) $request->get('keyword'))) {
            $query->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('code', 'like', "%{$keyword}%");
            });
        }

        if ($provinceId = $request->integer('province_id')) {
            $query->where('province_id', $provinceId);
        }

        if ($workTypeId = $request->integer('work_type_id')) {
            $query->where('work_type_id', $workTypeId);
        }

        if ($categoryId = $request->integer('category_id')) {
            $query->whereHas('categories', fn ($query) => $query->where('categories.id', $categoryId));
        }

        if ($status = $request->get('work_status')) {
            $query->where('work_status', $status);
        }

        if ($minPrice = $request->get('min_price')) {
            $query->where('price', '>=', (float) $minPrice);
        }

        if ($maxPrice = $request->get('max_price')) {
            $query->where('price', '<=', (float) $maxPrice);
        }
    }

    private function perPage(Request $request): int
    {
        return min(max($request->integer('per_page', 15), 1), 50);
    }
}
