<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeerReviewResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Traits\HasUser;
use Illuminate\Http\Request;

class MyUserController extends Controller
{
    use HasUser;

    /**
     * Devuelve el perfil del usuario autenticado.
     */
    public function getMyProfile()
    {
        $user = $this->authenticatedUser();
        $user->load(['profile']);

        // Contar solo seguidores y seguidos aceptados
        $followersCount = $user->followers()->wherePivot('status', 'accepted')->count();
        $followingCount = $user->following()->wherePivot('status', 'accepted')->count();
        $postsCount = $user->posts()->count();

        $distinctLocations = count($user->reviewedLocationIds());
        $distinctBeers = count($user->reviewedBeerIds());
        $distinctStyles = count($user->reviewedStyleIds());
        $distinctBreweries = count($user->reviewedBreweryIds());
        $distinctCountries = count($user->reviewedStyleCountries());
        $totalReviews = $user->beerReviews()->count();
        $totalFavorites = $user->favoritedBeers()->count();

        return response()->json([
            'data' => [new UserResource($user)],
            'stats' => [
                'distinct_locations' => $distinctLocations,
                'distinct_beers' => $distinctBeers,
                'distinct_styles' => $distinctStyles,
                'distinct_breweries' => $distinctBreweries,
                'distinct_countries' => $distinctCountries,
                'total_reviews' => $totalReviews,
                'total_favorites' => $totalFavorites,
                'followers' => $followersCount,
                'following' => $followingCount,
                'posts' => $postsCount,
            ]
        ]);
    }

    /**
     * Devuelve los seguidores del usuario autenticado.
     */
    public function getMyFollowers(Request $request)
    {
        $user = $this->authenticatedUser();
        $followers = $user->acceptedFollowers()
            ->with('profile')
            ->paginate($request->per_page ?? 15);
        return UserResource::collection($followers);
    }

    /**
     * Devuelve los usuarios que sigue el usuario autenticado.
     */
    public function getMyFollowing(Request $request)
    {
        $user = $this->authenticatedUser();
        $following = $user->acceptedFollowing()
            ->with('profile')
            ->paginate($request->per_page ?? 15);
        return UserResource::collection($following);
    }

    /**
     * Devuelve los posts del usuario autenticado.
     */
    public function getMyPosts(Request $request)
    {
        $user = $this->authenticatedUser();
        $posts = $user->posts()
            ->with([
                'user.profile', 
                'beer:id,name,brewery_id,style_id',
                'beer.brewery:id,name',
                'beer.style:id,name',
                'location:id,name,address,city,latitude,longitude', 
                'comments.user',
                'beerReview:id,post_id,beer_id,location_id,rating,review_text',
                'beerReview.beer:id,name,brewery_id,style_id',
                'beerReview.beer.brewery:id,name',
                'beerReview.beer.style:id,name',
                'beerReview.location:id,name,address,city,latitude,longitude'
            ])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate($request->per_page ?? 15);
        return PostResource::collection($posts);
    }

    /**
     * Devuelve las reseñas de cervezas del usuario autenticado.
     */
    public function getMyBeerReviews(Request $request)
    {
        $user = $this->authenticatedUser();
        $reviews = $user->beerReviews()
            ->with(['beer', 'user.profile'])
            ->latest()
            ->paginate($request->per_page ?? 15);
        return BeerReviewResource::collection($reviews);
    }

    /**
     * Devuelve las cervezas favoritas del usuario autenticado.
     */
    public function getMyFavoriteBeers(Request $request)
    {
        $user = $this->authenticatedUser();
        $favorites = $user->favoritedBeers()
            ->with(['style', 'brewery'])
            ->paginate($request->per_page ?? 15);
        return response()->json($favorites);
    }

    /**
     * Devuelve estadísticas del usuario autenticado.
     */
    public function getMyStats()
    {
        $user = $this->authenticatedUser();

        $distinctLocations = count($user->reviewedLocationIds());
        $distinctBeers = count($user->reviewedBeerIds());
        $distinctStyles = count($user->reviewedStyleIds());
        $distinctBreweries = count($user->reviewedBreweryIds());
        $distinctCountries = count($user->reviewedStyleCountries());
        $totalReviews = $user->beerReviews()->count();
        $totalFavorites = $user->favoritedBeers()->count();

        return response()->json([
            'distinct_locations' => $distinctLocations,
            'distinct_beers' => $distinctBeers,
            'distinct_styles' => $distinctStyles,
            'distinct_breweries' => $distinctBreweries,
            'distinct_countries' => $distinctCountries,
            'total_reviews' => $totalReviews,
            'total_favorites' => $totalFavorites,
        ]);
    }

    public function updateMyProfile(Request $request)
    {
        $user = $this->authenticatedUser();
        $user->update($request->only(['name', 'username', 'email']));

        // Actualizar perfil si existe
        if ($user->profile()) {
            $user->profile()->update($request->only(['bio', 'location', 'phone', 'website', 'facebook', 'twitter', 'instagram']));
        }

        return response()->json(new UserResource($user));
    }

    public function updateMyPrivacySettings(Request $request)
    {
        $user = $this->authenticatedUser();
        $user->update($request->only(['private_profile']));

        $user->profile()->update($request->only(['show_online_status', 'share_location', 'allow_mentions']));

        return response()->json(new UserResource($user));
    }
    
    public function updateMyNotificationsSettings(Request $request)
    {
        $user = $this->authenticatedUser();
    
        // Solo los campos permitidos
        $fields = [
            'email_notifications',
            'notify_new_followers',
            'notify_likes',
            'notify_comments',
            'notify_mentions',
            'notify_following_posts',
            'notify_recommendations',
            'notify_trends',
            'notify_direct_messages',
            'notify_group_messages',
            'notify_events',
            'notify_updates',
            'notify_security',
            'notify_promotions'
        ];
    
        // Actualizar solo si existe el perfil
        if ($user->profile()) {
            $user->profile()->update($request->only($fields));
        }
    
        return response()->json(new UserResource($user));
    }

}
