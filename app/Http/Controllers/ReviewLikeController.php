<?php

namespace App\Http\Controllers;

use App\Models\ReviewLike;
use Illuminate\Http\Request;

/**
 * Class ReviewLikeController
 * @package App\Http\Controllers
 */
class ReviewLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviewLikes = ReviewLike::paginate();

        return view('review-like.index', compact('reviewLikes'))
            ->with('i', (request()->input('page', 1) - 1) * $reviewLikes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $reviewLike = new ReviewLike();
        return view('review-like.create', compact('reviewLike'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(ReviewLike::$rules);

        $reviewLike = ReviewLike::create($request->all());

        return redirect()->route('review-likes.index')
            ->with('success', 'ReviewLike created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reviewLike = ReviewLike::find($id);

        return view('review-like.show', compact('reviewLike'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reviewLike = ReviewLike::find($id);

        return view('review-like.edit', compact('reviewLike'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ReviewLike $reviewLike
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReviewLike $reviewLike)
    {
        request()->validate(ReviewLike::$rules);

        $reviewLike->update($request->all());

        return redirect()->route('review-likes.index')
            ->with('success', 'ReviewLike updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $reviewLike = ReviewLike::find($id)->delete();

        return redirect()->route('review-likes.index')
            ->with('success', 'ReviewLike deleted successfully');
    }
}
