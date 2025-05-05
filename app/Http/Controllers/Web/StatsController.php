<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Beer;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Follow;
use App\Models\CheckIn;
use App\Models\Conversation;
use App\Models\Report;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'users'         => User::count(),
            'beers'         => Beer::count(),
            'posts'         => Post::count(),
            'comments'      => Comment::count(),
            'likes'         => Like::count(),
            'follows'       => Follow::count(),
            'check_ins'     => CheckIn::count(),
            'conversations' => Conversation::count(),
            'reports'       => Report::count(),
        ]);
    }
}
