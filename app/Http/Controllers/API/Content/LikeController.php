<?php

namespace App\Http\Controllers\API\Content;

use App\Events\PostLiked;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Like;
use App\Traits\HasUser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;

class LikeController extends Controller
{
    use HasUser;
    public function getPostLikes(Request $request, $id): JsonResponse
    {
        $post = Post::where('id', $id)->firstOrFail();
        
        $users = Like::getUsersWhoLiked($post);

        return response()->json(UserResource::collection($users));
    }

    public function togglePostLike(Request $request, $id): JsonResponse
    {
        $user = $this->authenticatedUser();
        $post = Post::where('id', $id)->firstOrFail();

        $wasAdded = Like::toggle($user->id, $post);

        if ($wasAdded) {
            event(new PostLiked($user, $post));
        }

        return response()->json([
            'liked' => $wasAdded,
            'likes_count' => Like::countFor($post)
        ]);
    }
}
