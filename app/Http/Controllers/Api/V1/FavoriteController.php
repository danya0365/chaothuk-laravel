<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FavoriteCollection;
use App\Http\Resources\Api\V1\FavoriteResource;
use App\Models\Favorite;
use App\Models\Recruit;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Bookmarks — "I want to come back to this listing".
 *
 * The caller's id comes from the token and never from the body, and the favorite
 * ids arrive from the path, so every lookup is scoped to user_id — a guessed id
 * from another user matches nothing. The morph target is renamed to the short
 * wire key ("work" / "recruit") at the edge and resolved back on the way in.
 */
class FavoriteController extends Controller
{
    /**
     * Listing class per wire key — the only morph targets the client sends.
     *
     * @var array<string, class-string>
     */
    private const TYPES = ['work' => Work::class, 'recruit' => Recruit::class];

    /**
     * The caller's bookmarks, newest first, with the full listing card rendered.
     *
     * `type` narrows to one side of the marketplace; without it the list is
     * interleaved and the client groups it by `type`.
     */
    public function index(Request $request): FavoriteCollection
    {
        $query = Favorite::query()->where('user_id', $request->user()->id);

        if ($type = $request->string('type')->value()) {
            $this->assertType((string) $type);
            $query->where('favoritable_type', self::TYPES[$type]);
        }

        // The card relations mirror WorkController — dots traverse the morph, so
        // one eager-load covers both listing kinds without lazy-loading per row.
        $favorites = $query->with(['favoritable.province', 'favoritable.workType', 'favoritable.author', 'favoritable.categories'])
            ->latest()
            ->limit(min(max($request->integer('per_page', 50), 1), 100))
            ->get()
            ->filter(fn (Favorite $favorite) => $favorite->favoritable !== null)
            ->values();

        return FavoriteCollection::make($favorites);
    }

    /**
     * Save a listing. Saving an already-saved listing is a no-op, not an error —
     * the client taps a heart that may lag behind.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in(array_keys(self::TYPES))],
            'id' => ['required', 'integer', 'min:1'],
        ]);

        $class = self::TYPES[$validated['type']];

        if (! $class::query()->whereKey($validated['id'])->exists()) {
            throw new AccessDeniedHttpException('ไม่พบประกาศนี้');
        }

        $favorite = Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'favoritable_type' => $class,
            'favoritable_id' => $validated['id'],
        ]);

        $favorite->load(['favoritable.province', 'favoritable.workType', 'favoritable.author', 'favoritable.categories']);

        return (new FavoriteResource($favorite))->response()->setStatusCode(201);
    }

    /**
     * Remove one bookmark. Removing is idempotent at the caller's scope: a stale
     * id simply disappears, so a double tap never 404s.
     */
    public function destroy(Request $request, int $favorite): Response
    {
        $deleted = Favorite::query()
            ->where('user_id', $request->user()->id)
            ->whereKey($favorite)
            ->delete();

        if (! $deleted) {
            throw new AccessDeniedHttpException('ไม่พบรายการโปรดนี้');
        }

        return response()->noContent();
    }

    /**
     * Whether the caller has one listing saved — for the filled heart on a
     * detail page, cheaper than reading the whole list.
     */
    public function check(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in(array_keys(self::TYPES))],
            'favoritable_id' => ['required', 'integer', 'min:1'],
        ]);

        // The row id travels back too — the client toggles the heart by DELETE,
        // and DELETE wants the favorite id, not the listing id.
        $favorite = Favorite::query()
            ->where('user_id', $request->user()->id)
            ->where('favoritable_type', self::TYPES[$validated['type']])
            ->where('favoritable_id', $validated['favoritable_id'])
            ->first();

        return response()->json(['data' => [
            'favorited' => (bool) $favorite,
            'id' => $favorite?->id,
        ]]);
    }

    /**
     * An unknown morph key is a client bug, not an empty result.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function assertType(string $type): void
    {
        if (! isset(self::TYPES[$type])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'type' => 'ชนิดรายการโปรดไม่ถูกต้อง',
            ]);
        }
    }
}
