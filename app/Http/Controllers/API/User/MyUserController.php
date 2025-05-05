<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Traits\HasUser;

class MyUserController extends Controller
{
    use HasUser;

    /**
     * Devuelve el perfil del usuario autenticado.
     */
    public function getMyProfile()
    {
        $user = $this->authenticatedUser()->load('profile');
        return response()->json(new UserResource($user));
    }

    /**
     * Devuelve los seguidores del usuario autenticado.
     */
    public function getMyFollowers()
    {
        $user = $this->authenticatedUser();
        $followers = $user->acceptedFollowers()->with('profile')->get();
        return response()->json(UserResource::collection($followers));
    }

    /**
     * Devuelve los usuarios que sigue el usuario autenticado.
     */
    public function getMyFollowing()
    {
        $user = $this->authenticatedUser();
        $following = $user->acceptedFollowing()->with('profile')->get();
        return response()->json(UserResource::collection($following));
    }

    /**
     * Devuelve los posts del usuario autenticado.
     */
    public function getMyPosts()
    {
        $user = $this->authenticatedUser();
        $posts = $user->posts()->with(['user', 'beer', 'location'])->latest()->paginate(15);
        return response()->json(PostResource::collection($posts));
    }

    /**
     * Devuelve estadísticas del usuario autenticado.
     */
    public function getMyStats()
    {
        $user = $this->authenticatedUser();

        $distinctBeers = $user->posts()->distinct('beer_id')->count('beer_id');
        $distinctLocations = $user->posts()->whereNotNull('location_id')->distinct('location_id')->count('location_id');
        $distinctStyles = $user->posts()
            ->join('beers', 'posts.beer_id', '=', 'beers.id')
            ->distinct('beers.style_id')
            ->count('beers.style_id');

        return response()->json([
            'distinct_beers'     => $distinctBeers,
            'distinct_locations' => $distinctLocations,
            'distinct_styles'    => $distinctStyles,
        ]);
    }
}
