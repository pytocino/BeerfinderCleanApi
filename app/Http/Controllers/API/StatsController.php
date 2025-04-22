<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeerResource;
use App\Models\User;
use App\Models\Beer;
use App\Models\Follow;
use App\Models\CheckIn;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * @group Estadísticas
 *
 * APIs para obtener estadísticas y recomendaciones
 */
class StatsController extends Controller
{
    /**
     * Estadísticas de usuario
     *
     * Obtiene estadísticas detalladas sobre la actividad del usuario.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del usuario. Example: 1
     *
     * @response {
     *  "data": {
     *    "user": {
     *      "id": 1,
     *      "name": "Juan Pérez",
     *      "profile_picture": "https://example.com/avatars/juan.jpg"
     *    },
     *    "total_check_ins": 42,
     *    "unique_beers": 38,
     *    "avg_rating": 3.8,
     *    "favorite_styles": [
     *      {
     *        "name": "IPA",
     *        "count": 15
     *      },
     *      {
     *        "name": "Stout",
     *        "count": 8
     *      }
     *    ],
     *    "favorite_breweries": [
     *      {
     *        "id": 3,
     *        "name": "Founders Brewing Co.",
     *        "count": 7
     *      }
     *    ],
     *    "top_rated_beers": [
     *      {
     *        "id": 15,
     *        "name": "KBS (Kentucky Breakfast Stout)",
     *        "brewery": "Founders Brewing Co.",
     *        "rating": 4.9
     *      }
     *    ],
     *    "check_ins_by_month": {
     *      "2023-01": 5,
     *      "2023-02": 8,
     *      "2023-03": 12,
     *      "2023-04": 17
     *    }
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el usuario solicitado."
     * }
     */
    public function getUserStats(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Estadísticas básicas
            $checkIns = CheckIn::where('user_id', $user->id)->get();
            $totalCheckIns = $checkIns->count();
            $uniqueBeers = $checkIns->pluck('beer_id')->unique()->count();
            $avgRating = $checkIns->avg('rating');

            // Estilos favoritos
            $favoriteStyles = CheckIn::where('user_id', $user->id)
                ->join('beers', 'check_ins.beer_id', '=', 'beers.id')
                ->join('beer_styles', 'beers.style_id', '=', 'beer_styles.id')
                ->select('beer_styles.name')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('beer_styles.name')
                ->orderByDesc('count')
                ->limit(5)
                ->get();

            // Cervecerías favoritas
            $favoriteBreweries = CheckIn::where('user_id', $user->id)
                ->join('beers', 'check_ins.beer_id', '=', 'beers.id')
                ->join('breweries', 'beers.brewery_id', '=', 'breweries.id')
                ->select('breweries.id', 'breweries.name')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('breweries.id', 'breweries.name')
                ->orderByDesc('count')
                ->limit(5)
                ->get();

            // Cervezas mejor valoradas
            $topRatedBeers = CheckIn::where('user_id', $user->id)
                ->join('beers', 'check_ins.beer_id', '=', 'beers.id')
                ->join('breweries', 'beers.brewery_id', '=', 'breweries.id')
                ->select('beers.id', 'beers.name', 'breweries.name as brewery')
                ->selectRaw('rating')
                ->orderByDesc('rating')
                ->orderByDesc('check_ins.created_at')
                ->limit(5)
                ->get();

            // Check-ins por mes (últimos 6 meses)
            $startDate = Carbon::now()->subMonths(6);
            $checkInsByMonth = CheckIn::where('user_id', $user->id)
                ->where('created_at', '>=', $startDate)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();

            return response()->json([
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'profile_picture' => $user->profile_picture,
                    ],
                    'total_check_ins' => $totalCheckIns,
                    'unique_beers' => $uniqueBeers,
                    'avg_rating' => round($avgRating ?? 0, 1),
                    'favorite_styles' => $favoriteStyles,
                    'favorite_breweries' => $favoriteBreweries,
                    'top_rated_beers' => $topRatedBeers,
                    'check_ins_by_month' => $checkInsByMonth
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el usuario solicitado.'], 404);
        }
    }

    /**
     * Recomendaciones para el usuario
     *
     * Obtiene recomendaciones de cervezas personalizadas para el usuario autenticado.
     *
     * @authenticated
     *
     * @queryParam limit integer Número de recomendaciones a obtener (1-20). Example: 10
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 25,
     *      "name": "La Chouffe",
     *      "brewery": {
     *        "id": 12,
     *        "name": "Brasserie d'Achouffe"
     *      },
     *      "style": {
     *        "id": 6,
     *        "name": "Belgian Strong Golden Ale"
     *      },
     *      "abv": 8.0,
     *      "description": "Cerveza belga dorada con notas de frutas y especias",
     *      "image_url": "https://example.com/beers/lachouffe.png",
     *      "rating_avg": 4.2,
     *      "check_ins_count": 87,
     *      "recommendation_reason": "Basado en tu gusto por cervezas belgas"
     *    }
     *  ]
     * }
     */
    public function getRecommendations(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'limit' => 'nullable|integer|min:1|max:20',
        ]);

        $limit = $validated['limit'] ?? 10;
        $user = $request->user();

        // Estrategia de recomendación:
        // 1. Encontrar los estilos que más le gustan al usuario (basado en ratings)
        // 2. Encontrar las cervezas mejor valoradas de esos estilos que el usuario aún no ha probado
        // 3. Encontrar cervezas populares entre los usuarios que sigue
        // 4. Combinar los resultados en una lista única y diversa

        // 1. Encontrar los estilos favoritos del usuario
        $favoriteStyles = CheckIn::where('user_id', $user->id)
            ->join('beers', 'check_ins.beer_id', '=', 'beers.id')
            ->where('rating', '>=', 3.5) // Solo cervezas bien valoradas
            ->pluck('beers.style_id')
            ->countBy()
            ->sortDesc()
            ->take(3)
            ->keys()
            ->toArray();

        // 2. Cervezas probadas por el usuario
        $triedBeers = CheckIn::where('user_id', $user->id)
            ->pluck('beer_id')
            ->toArray();

        // 3. Usuarios seguidos
        $followedUsers = Follow::where('follower_id', $user->id)
            ->pluck('followed_id')
            ->toArray();

        // Inicializar colección de recomendaciones
        $recommendations = collect();

        // Algoritmo de recomendación

        // A. Recomendaciones basadas en estilos favoritos (50% del límite)
        if (!empty($favoriteStyles)) {
            $styleBasedRecommendations = Beer::whereIn('style_id', $favoriteStyles)
                ->whereNotIn('id', $triedBeers)
                ->with(['brewery', 'style'])
                ->withAvg('checkIns', 'rating')
                ->withCount('checkIns')
                ->having('check_ins_avg_rating', '>=', 3.8)
                ->having('check_ins_count', '>=', 5)
                ->orderByDesc('check_ins_avg_rating')
                ->limit(intval($limit * 0.5))
                ->get();

            foreach ($styleBasedRecommendations as $beer) {
                $beer->recommendation_reason = "Basado en tu gusto por cervezas {$beer->style->name}";
                $recommendations->push($beer);
            }
        }

        // B. Recomendaciones basadas en usuarios seguidos (30% del límite)
        if (!empty($followedUsers)) {
            $followBasedRecommendations = CheckIn::whereIn('user_id', $followedUsers)
                ->where('rating', '>=', 4.0)
                ->join('beers', 'check_ins.beer_id', '=', 'beers.id')
                ->whereNotIn('beers.id', $triedBeers)
                ->whereNotIn('beers.id', $recommendations->pluck('id')->toArray())
                ->select('beers.*')
                ->selectRaw('AVG(check_ins.rating) as avg_user_rating')
                ->with(['brewery', 'style'])
                ->withAvg('checkIns', 'rating')
                ->withCount('checkIns')
                ->groupBy('beers.id')
                ->orderByDesc('avg_user_rating')
                ->limit(intval($limit * 0.3))
                ->get();

            foreach ($followBasedRecommendations as $beer) {
                $beer->recommendation_reason = "Popular entre las personas que sigues";
                $recommendations->push($beer);
            }
        }

        // C. Recomendaciones populares globales (20% del límite)
        $remainingNeeded = $limit - $recommendations->count();
        if ($remainingNeeded > 0) {
            $popularRecommendations = Beer::whereNotIn('id', $triedBeers)
                ->whereNotIn('id', $recommendations->pluck('id')->toArray())
                ->with(['brewery', 'style'])
                ->withAvg('checkIns', 'rating')
                ->withCount('checkIns')
                ->having('check_ins_avg_rating', '>=', 4.0)
                ->having('check_ins_count', '>=', 10)
                ->orderByDesc('check_ins_count')
                ->limit($remainingNeeded)
                ->get();

            foreach ($popularRecommendations as $beer) {
                $beer->recommendation_reason = "Muy popular en la comunidad";
                $recommendations->push($beer);
            }
        }

        // Transformar a recursos y devolver
        $resourceCollection = $recommendations->map(function ($beer) {
            $resource = new BeerResource($beer);
            $data = $resource->toArray(request());
            $data['recommendation_reason'] = $beer->recommendation_reason;
            return $data;
        });

        return response()->json(['data' => $resourceCollection]);
    }
}
