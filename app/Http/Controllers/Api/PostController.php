<?php

namespace App\Http\Controllers\Api;

use App\Enums\NotificationType;
use App\Enums\Permission;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserCollection;
use App\Models\Post;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Get Posts
     */
    public function getPosts(Request $request)
    {
        $query = Post::with(['author'])->whereNull('parent_id');

        $keyword = trim($request->get('keyword'));
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('content', 'LIKE', "%{$keyword}%");
            });
        }

        $authorId = trim($request->get('author_id'));
        if ($authorId) {
            $query->where('author_id', $authorId);
        }

        $rating = trim($request->get('rating'));
        if ($rating) {
            $query->where('rating', $rating);
        }

        $data = $query->orderBy('created_at', 'desc')
            ->limitOffset(request()->all())->get();

        return response()->json([
            'status' => true,
            'data'   => new PostCollection($data),
        ], 200);
    }

    /**
     * Get Post Detail with replies
     */
    public function getPost(Request $request, $id)
    {
        $data = Post::with(['author', 'replies.author'])->find($id);
        return response()->json([
            'status' => $data ? true : false,
            'data'   => $data ? new PostResource($data) : null,
        ], 200);
    }

    /**
     * Create Post / Review
     */
    public function createPost(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth('sanctum')->user();

        $post = $request->only(['title', 'content', 'images', 'rating', 'parent_id']);
        $post['author_id'] = $user->id;

        $validatedRequest = Validator::make($post, [
            'content'   => 'required',
            'rating'    => 'nullable|integer|min:1|max:5',
            'parent_id' => 'nullable|exists:posts,id',
        ]);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status'  => false,
                'message' => implode(',', $validatedRequest->messages()->all()),
                'errors'  => $validatedRequest->errors(),
            ], 401);
        }

        if (isset($post['images']) && is_array($post['images'])) {
            $post['images'] = array_filter(array_map('trim', $post['images']));
        } else {
            $post['images'] = [];
        }

        try {
            $newPost = Post::create($post);
            return response()->json([
                'status' => true,
                'data'   => new PostResource($newPost->load('author')),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Update own Post
     */
    public function updatePost(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = auth('sanctum')->user();

        $data = Post::find($id);

        if (!$data) {
            return response()->json(['status' => false, 'message' => 'not found'], 404);
        }

        if ($data->author_id !== $user->id) {
            return response()->json(['status' => false, 'message' => 'no permission'], 403);
        }

        $post = $request->only(['title', 'content', 'images', 'rating']);

        $validatedRequest = Validator::make($post, [
            'content' => 'required',
            'rating'  => 'nullable|integer|min:1|max:5',
        ]);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status'  => false,
                'message' => implode(',', $validatedRequest->messages()->all()),
                'errors'  => $validatedRequest->errors(),
            ], 401);
        }

        if (isset($post['images']) && is_array($post['images'])) {
            $post['images'] = array_filter(array_map('trim', $post['images']));
        }

        try {
            $data->update($post);
            return response()->json([
                'status' => true,
                'data'   => new PostResource($data->fresh()->load('author')),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete own Post (soft delete)
     */
    public function deletePost(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = auth('sanctum')->user();

        $data = Post::find($id);

        if (!$data) {
            return response()->json(['status' => false, 'message' => 'not found'], 404);
        }

        if ($data->author_id !== $user->id) {
            return response()->json(['status' => false, 'message' => 'no permission'], 403);
        }

        try {
            $data->delete();
            return response()->json(['status' => true, 'message' => 'deleted'], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle Like on a Post
     */
    public function createPostLike(Request $request, int $postId)
    {
        /** @var \App\Models\User $user */
        $user = auth('sanctum')->user();

        $validatedRequest = Validator::make(['post_id' => $postId], [
            'post_id' => 'required|exists:posts,id',
        ]);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status'  => false,
                'message' => implode(',', $validatedRequest->messages()->all()),
                'errors'  => $validatedRequest->errors(),
            ], 401);
        }

        try {
            $post = Post::with('author')->find($postId);

            if ($user->likedPosts()->get()->contains($post)) {
                $user->likedPosts()->detach($post->id);
            } else {
                $user->likedPosts()->attach($post->id);
            }

            return response()->json([
                'status'  => true,
                'message' => 'success',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Get Post Like Count
     */
    public function getPostLikeCount(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['status' => false, 'data' => null], 200);
        }

        return response()->json([
            'status' => true,
            'data'   => $post->userLikes()->count(),
        ], 200);
    }

    /**
     * Get users who liked a post
     */
    public function getPostLikes(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['status' => false, 'data' => null], 200);
        }

        $data = $post->userLikes()
            ->orderBy('post_likes.created_at', 'desc')
            ->limitOffset(request()->all())->get();

        return response()->json([
            'status' => true,
            'data'   => new UserCollection($data),
        ], 200);
    }
}
