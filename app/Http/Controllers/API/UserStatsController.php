<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\CheckIn;
use App\Models\Comment;
use App\Models\Follow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserStatsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $userId = $request->id;

        // Número de cervezas distintas tomadas (por posts)
        $distinctBeers = Post::where('user_id', '=', $userId)->distinct('beer_id')->count('beer_id');

        // Número de lugares distintos visitados (por posts)
        $distinctLocations = Post::where('user_id', '=', $userId)->whereNotNull('location_id')->distinct('location_id')->count('location_id');

        // Número de estilos de cerveza distintos probados
        $distinctStyles = Post::where('user_id', '=', $userId)
            ->join('beers', 'posts.beer_id', '=', 'beers.id')
            ->distinct('beers.style_id')
            ->count('beers.style_id');

        return response()->json([
            'distinct_beers'   => $distinctBeers,
            'distinct_locations' => $distinctLocations,
            'distinct_styles'  => $distinctStyles,
        ]);
    }
}
