<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostJsonApiResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Override;
use Throwable;

class PostController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware(
                middleware: 'auth:sanctum',
                except: ['index', 'show']
            )
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::published()
            ->with([
                'category:id,name',
                'user:id,name,username,avatar',
            ])
            ->paginate();

        return $posts->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request, PostService $service): JsonResponse
    {
        try {
            /**
             * @var \App\Models\User
             */
            $user = Auth::guard('sanctum')->user();

            if (!$user->currentAccessToken()->can('posts.create')) {
                return Response::json([
                    'status' => 'forbidden',
                    'message' => 'You are not allowed to create posts',
                ], 403);
            }

            $post = $service->create($request);
            return Response::json([
                'data' => $post->refresh(),
            ], 201);
        } catch (Throwable $e) {
            //return new JsonResponse();
            return Response::json([
                'status' => 'error',
                'message' => 'Failed to create post: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if ($post->status !== PostStatus::Published) {
            //abort(404);
        }
        $post->load(['category:id,name', 'user:id,name,username,avatar']);

        return $post->toResource(PostJsonApiResource::class);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, PostService $service, Post $post)
    {
        try {
            $post = $service->update($post, $request);
            return Response::json([
                'data' => $post,
            ], 201);
        } catch (Throwable $e) {
            //return new JsonResponse();
            return Response::json([
                'status' => 'error',
                'message' => 'Failed to create post: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return Response::noContent();
    }
}
