<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PortfolioCollection;
use App\Http\Resources\Api\V1\PortfolioResource;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provider portfolios — the finished works a user can show off.
 *
 * The web holds the whole CRUD, so the API does the same: the list, the create
 * and the edit are owner-eyes only, while the detail view is the public face a
 * customer can open from anywhere.
 */
class PortfolioController extends Controller
{
    /**
     * The caller's own portfolio entries, newest first.
     */
    public function index(Request $request): PortfolioCollection
    {
        $portfolios = Portfolio::query()
            ->with(['workType'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return PortfolioCollection::make($portfolios);
    }

    /**
     * Add one finished work — web validation mirrored word for word.
     */
    public function store(Request $request): PortfolioResource
    {
        $validated = $request->validate([
            'title' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:5'],
            'work_type_id' => ['required', 'exists:work_types,id'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['string'],
        ]);

        $portfolio = Portfolio::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        $portfolio->load(['user', 'workType']);

        return new PortfolioResource($portfolio);
    }

    /**
     * The public face of one portfolio entry — anyone may look.
     */
    public function show(Portfolio $portfolio): PortfolioResource
    {
        $portfolio->load(['user', 'workType']);

        return new PortfolioResource($portfolio);
    }

    /**
     * Edit one of the caller's own entries — like the web edit form.
     */
    public function update(Request $request, Portfolio $portfolio): PortfolioResource
    {
        $this->callerOwns($request, $portfolio);

        $validated = $request->validate([
            'title' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:5'],
            'work_type_id' => ['required', 'exists:work_types,id'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['string'],
        ]);

        $portfolio->update($validated);

        $portfolio->load(['user', 'workType']);

        return new PortfolioResource($portfolio);
    }

    /**
     * Remove one of the caller's own entries.
     *
     * Deleting an already-deleted entry stays silent, anyone else's row keeps
     * its refusal — a guessed id never removes someone  else's work.
     */
    public function destroy(Request $request, int $portfolio): \Illuminate\Http\Response
    {
        /** @var \App\Models\Portfolio|null $row */
        $row = Portfolio::query()->find($portfolio);

        if (! $row) {
            /** @var \App\Models\Portfolio|null $trashed */
            $trashed = Portfolio::withTrashed()->find($portfolio);

            if (! $trashed) {
                throw new AccessDeniedHttpException('ไม่พบผลงานนี้');
            }
        } elseif ($row->user_id !== $request->user()->id) {
            throw new AccessDeniedHttpException('คุณลบได้เฉพาะผลงานของตัวเอง');
        } else {
            $row->delete();
        }

        return response()->noContent();
    }

    /**
     * The portfolio row — but only if the caller owns it.
     */
    private function callerOwns(Request $request, Portfolio $portfolio): void
    {
        if ($portfolio->user_id !== $request->user()->id) {
            throw new AccessDeniedHttpException('คุณแก้ไขได้เฉพาะผลงานของตัวเอง');
        }
    }
}
