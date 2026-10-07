<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Permission;
use App\Enums\RecruitStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreRecruitRequest;
use App\Http\Resources\Api\V1\RecruitCollection;
use App\Http\Resources\Api\V1\RecruitResource;
use App\Models\Recruit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Recruits — "I need someone to do this job".
 */
class RecruitController extends Controller
{
    /**
     * @var array<int, string>
     */
    private const CARD_RELATIONS = ['province', 'workType', 'author'];

    public function index(Request $request): RecruitCollection
    {
        $query = Recruit::query()->with(self::CARD_RELATIONS);

        $this->applyFilters($query, $request);

        $recruits = $query
            ->orderBy('display_priority', 'desc')
            ->orderByDesc('created_at')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return RecruitCollection::make($recruits);
    }

    public function show(Recruit $recruit): RecruitResource
    {
        $recruit->load(['province', 'workType', 'author', 'categories']);

        return new RecruitResource($recruit);
    }

    /**
     * @throws AccessDeniedHttpException when the account may not post recruits
     */
    public function store(StoreRecruitRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->isPermission(Permission::CREATE_RECRUIT->value)) {
            throw new AccessDeniedHttpException('คุณยังไม่มีสิทธิ์ลงประกาศหาคน');
        }

        $post = $request->validated();

        // author_id comes from the token, never from the request body.
        $recruit = Recruit::create([
            'title' => $post['title'],
            'description' => $post['description'],
            'details' => $post['details'] ?? null,
            'budget' => $post['budget'],
            'primary_image' => $post['primary_image'] ?? null,
            'images' => $post['images'] ?? null,
            'province_id' => $post['province_id'],
            'work_type_id' => $post['work_type_id'],
            'latitude' => $post['latitude'] ?? null,
            'longitude' => $post['longitude'] ?? null,
            'recruit_status' => RecruitStatus::STAND_BY->value,
            'author_id' => $user->id,
        ]);

        if (! empty($post['categories'])) {
            $recruit->categories()->sync($post['categories']);
        }

        $recruit->load(self::CARD_RELATIONS);

        return (new RecruitResource($recruit))->response()->setStatusCode(201);
    }

    /**
     * @param  Builder<Recruit>  $query
     */
    private function applyFilters(Builder $query, Request $request): void
    {
        if ($keyword = trim((string) $request->get('keyword'))) {
            $query->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
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

        if ($status = $request->get('recruit_status')) {
            $query->where('recruit_status', $status);
        }

        if ($minBudget = $request->get('min_budget')) {
            $query->where('budget', '>=', (float) $minBudget);
        }

        if ($maxBudget = $request->get('max_budget')) {
            $query->where('budget', '<=', (float) $maxBudget);
        }
    }

    private function perPage(Request $request): int
    {
        return min(max($request->integer('per_page', 15), 1), 50);
    }
}
