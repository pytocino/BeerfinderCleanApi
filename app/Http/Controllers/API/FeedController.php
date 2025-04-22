<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CheckInResource;
use App\Models\CheckIn;
use App\Models\Follow;
use App\Models\Like;
use App\Models\Beer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

/**
 * @group Feed de Actividad
 *
 * APIs para obtener diferentes feeds de actividad en la plataforma
 */
class FeedController extends Controller
{
    /**
     * Feed principal
     *
     * Obtiene un feed general de actividad reciente en la plataforma.
     *
     * @authenticated
     *
     * @queryParam type string Filtrar por tipo de check-in (beer, brewery, style). Example: beer
     * @queryParam time_range string Rango de tiempo (today, week, month, all). Example: week
     * @queryParam min_rating float Calificación mínima para filtrar (0-5). Example: 3.5
     * @queryParam location_id integer ID de la ubicación para filtrar. Example: 1
     * @queryParam sort string Ordenar por: recent, popular. Example: popular
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 42,
     *      "user": {
     *        "id": 3,
     *        "name": "Carlos Ruiz",
     *        "profile_picture": "https://example.com/avatars/carlos.jpg"
     *      },
     *      "beer": {
     *        "id": 5,
     *        "name": "Founders Breakfast Stout",
     *        "brewery": {
     *          "id": 3,
     *          "name": "Founders Brewing Co."
     *        }
     *      },
     *      "rating": 4.5,
     *      "comment": "Excelente balance entre café y chocolate",
     *      "photo_url": "https://example.com/photos/check_in_42.jpg",
     *      "location": {
     *        "id": 2,
     *        "name": "Beer Garden Madrid"
     *      },
     *      "likes_count": 15,
     *      "comments_count": 3,
     *      "is_liked": false,
     *      "created_at": "2023-04-18T18:30:00.000000Z"
     *    }
     *  ],
     *  "links": {...},
     *  "meta": {...}
     * }
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'type' => 'nullable|string|in:beer,brewery,style',
            'time_range' => 'nullable|string|in:today,week,month,all',
            'min_rating' => 'nullable|numeric|min:0|max:5',
            'location_id' => 'nullable|integer|exists:locations,id',
            'sort' => 'nullable|string|in:recent,popular',
            'per_page' => 'nullable|integer|min:5|max:50',
        ]);

        $query = $this->buildFeedQuery($request, $validated);

        $perPage = $validated['per_page'] ?? 10;

        return CheckInResource::collection($query->paginate($perPage));
    }

    /**
     * Feed de amigos
     *
     * Obtiene un feed de actividad de los usuarios que sigues.
     *
     * @authenticated
     *
     * @queryParam type string Filtrar por tipo de check-in (beer, brewery, style). Example: beer
     * @queryParam time_range string Rango de tiempo (today, week, month, all). Example: week
     * @queryParam min_rating float Calificación mínima para filtrar (0-5). Example: 3.5
     * @queryParam sort string Ordenar por: recent, popular. Example: recent
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 56,
     *      "user": {
     *        "id": 7,
     *        "name": "María López",
     *        "profile_picture": "https://example.com/avatars/maria.jpg"
     *      },
     *      "beer": {
     *        "id": 12,
     *        "name": "La Cibeles Rubia",
     *        "brewery": {
     *          "id": 8,
     *          "name": "Cervezas La Cibeles"
     *        }
     *      },
     *      "rating": 4.0,
     *      "comment": "Muy refrescante. Ideal para el verano.",
     *      "photo_url": null,
     *      "location": {
     *        "id": 5,
     *        "name": "Cervecería Internacional"
     *      },
     *      "likes_count": 8,
     *      "comments_count": 2,
     *      "is_liked": true,
     *      "created_at": "2023-04-17T14:20:00.000000Z"
     *    }
     *  ],
     *  "links": {...},
     *  "meta": {...}
     * }
     */
    public function friendsFeed(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'type' => 'nullable|string|in:beer,brewery,style',
            'time_range' => 'nullable|string|in:today,week,month,all',
            'min_rating' => 'nullable|numeric|min:0|max:5',
            'sort' => 'nullable|string|in:recent,popular',
            'per_page' => 'nullable|integer|min:5|max:50',
        ]);

        // Obtener IDs de usuarios seguidos
        $followedUserIds = Follow::where('follower_id', $request->user()->id)
            ->pluck('following_id')  // Reemplaza 'followed_id' con el nombre correcto
            ->toArray();

        if (empty($followedUserIds)) {
            // Si no sigue a nadie, devolver una colección vacía
            return CheckInResource::collection(
                CheckIn::where('id', 0)->paginate($validated['per_page'] ?? 10)
            );
        }

        $query = $this->buildFeedQuery($request, $validated);

        // Filtrar por usuarios seguidos
        $query->whereIn('check_ins.user_id', $followedUserIds);

        $perPage = $validated['per_page'] ?? 10;

        return CheckInResource::collection($query->paginate($perPage));
    }

    /**
     * Feed popular
     *
     * Obtiene un feed de los check-ins más populares en la plataforma.
     *
     * @authenticated
     *
     * @queryParam type string Filtrar por tipo de check-in (beer, brewery, style). Example: beer
     * @queryParam time_range string Rango de tiempo (today, week, month, all). Example: week
     * @queryParam min_rating float Calificación mínima para filtrar (0-5). Example: 4.0
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 78,
     *      "user": {
     *        "id": 4,
     *        "name": "Ana Martínez",
     *        "profile_picture": "https://example.com/avatars/ana.jpg"
     *      },
     *      "beer": {
     *        "id": 22,
     *        "name": "Westvleteren 12",
     *        "brewery": {
     *          "id": 15,
     *          "name": "Brouwerij Westvleteren"
     *        }
     *      },
     *      "rating": 5.0,
     *      "comment": "La mejor cerveza trapista. Una experiencia única.",
     *      "photo_url": "https://example.com/photos/check_in_78.jpg",
     *      "location": null,
     *      "likes_count": 45,
     *      "comments_count": 12,
     *      "is_liked": false,
     *      "created_at": "2023-04-12T20:15:00.000000Z"
     *    }
     *  ],
     *  "links": {...},
     *  "meta": {...}
     * }
     */
    public function popularFeed(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'type' => 'nullable|string|in:beer,brewery,style',
            'time_range' => 'nullable|string|in:today,week,month,all',
            'min_rating' => 'nullable|numeric|min:0|max:5',
            'per_page' => 'nullable|integer|min:5|max:50',
        ]);

        // Usar los mismos filtros pero forzar ordenamiento por popularidad
        $validated['sort'] = 'popular';

        $query = $this->buildFeedQuery($request, $validated);

        // Asegurar que solo se muestran check-ins con al menos algunos likes
        $query->having('likes_count', '>', 0);

        $perPage = $validated['per_page'] ?? 10;

        return CheckInResource::collection($query->paginate($perPage));
    }

    /**
     * Cervecerías en tendencia
     *
     * Obtiene un listado de las cervecerías más activas y populares actualmente.
     *
     * @authenticated
     *
     * @queryParam time_range string Rango de tiempo (today, week, month, all). Example: week
     * @queryParam limit integer Número de cervecerías a mostrar (1-20). Example: 5
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 3,
     *      "name": "Founders Brewing Co.",
     *      "logo_url": "https://example.com/logos/founders.png",
     *      "country": "Estados Unidos",
     *      "check_ins_count": 87,
     *      "avg_rating": 4.3,
     *      "trending_factor": 156
     *    }
     *  ]
     * }
     */
    public function trendingBreweries(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'time_range' => 'nullable|string|in:today,week,month,all',
            'limit' => 'nullable|integer|min:1|max:20',
        ]);

        $timeRange = $validated['time_range'] ?? 'week';
        $limit = $validated['limit'] ?? 10;

        // Establecer la fecha de inicio según el rango de tiempo
        $startDate = $this->getStartDateFromTimeRange($timeRange);

        // Consulta para cervecerías en tendencia
        $breweries = DB::table('breweries')
            ->join('beers', 'breweries.id', '=', 'beers.brewery_id')
            ->join('check_ins', 'beers.id', '=', 'check_ins.beer_id')
            ->select(
                'breweries.id',
                'breweries.name',
                'breweries.logo_url',
                'breweries.country'
            )
            ->selectRaw('COUNT(check_ins.id) as check_ins_count')
            ->selectRaw('AVG(check_ins.rating) as avg_rating')
            ->selectRaw('(COUNT(check_ins.id) * 1.5 + COUNT(DISTINCT check_ins.user_id) * 3) as trending_factor')
            ->where('check_ins.created_at', '>=', $startDate)
            ->groupBy('breweries.id', 'breweries.name', 'breweries.logo_url', 'breweries.country')
            ->having('check_ins_count', '>=', 5)
            ->orderByDesc('trending_factor')
            ->limit($limit)
            ->get();

        // Formatear los resultados
        $formattedBreweries = $breweries->map(function ($brewery) {
            return [
                'id' => $brewery->id,
                'name' => $brewery->name,
                'logo_url' => $brewery->logo_url,
                'country' => $brewery->country,
                'check_ins_count' => $brewery->check_ins_count,
                'avg_rating' => round($brewery->avg_rating, 1),
                'trending_factor' => (int) $brewery->trending_factor
            ];
        });

        return response()->json(['data' => $formattedBreweries]);
    }

    /**
     * Estilos en tendencia
     *
     * Obtiene un listado de los estilos de cerveza más populares actualmente.
     *
     * @authenticated
     *
     * @queryParam time_range string Rango de tiempo (today, week, month, all). Example: week
     * @queryParam limit integer Número de estilos a mostrar (1-20). Example: 5
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 8,
     *      "name": "New England IPA",
     *      "check_ins_count": 124,
     *      "avg_rating": 4.1,
     *      "unique_beers_count": 32
     *    }
     *  ]
     * }
     */
    public function trendingStyles(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'time_range' => 'nullable|string|in:today,week,month,all',
            'limit' => 'nullable|integer|min:1|max:20',
        ]);

        $timeRange = $validated['time_range'] ?? 'week';
        $limit = $validated['limit'] ?? 10;

        // Establecer la fecha de inicio según el rango de tiempo
        $startDate = $this->getStartDateFromTimeRange($timeRange);

        // Consulta para estilos en tendencia
        $styles = DB::table('beer_styles')
            ->join('beers', 'beer_styles.id', '=', 'beers.style_id')
            ->join('check_ins', 'beers.id', '=', 'check_ins.beer_id')
            ->select(
                'beer_styles.id',
                'beer_styles.name'
            )
            ->selectRaw('COUNT(check_ins.id) as check_ins_count')
            ->selectRaw('AVG(check_ins.rating) as avg_rating')
            ->selectRaw('COUNT(DISTINCT beers.id) as unique_beers_count')
            ->where('check_ins.created_at', '>=', $startDate)
            ->groupBy('beer_styles.id', 'beer_styles.name')
            ->having('check_ins_count', '>=', 5)
            ->orderByDesc('check_ins_count')
            ->limit($limit)
            ->get();

        return response()->json(['data' => $styles]);
    }

    /**
     * Actividad de una cerveza
     *
     * Obtiene un feed de actividad relacionada con una cerveza específica.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la cerveza. Example: 5
     * @queryParam time_range string Rango de tiempo (today, week, month, all). Example: month
     * @queryParam sort string Ordenar por: recent, popular. Example: recent
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 42,
     *      "user": {
     *        "id": 3,
     *        "name": "Carlos Ruiz",
     *        "profile_picture": "https://example.com/avatars/carlos.jpg"
     *      },
     *      "rating": 4.5,
     *      "comment": "Excelente balance entre café y chocolate",
     *      "photo_url": "https://example.com/photos/check_in_42.jpg",
     *      "location": {
     *        "id": 2,
     *        "name": "Beer Garden Madrid"
     *      },
     *      "likes_count": 15,
     *      "comments_count": 3,
     *      "is_liked": false,
     *      "created_at": "2023-04-18T18:30:00.000000Z"
     *    }
     *  ],
     *  "beer": {
     *    "id": 5,
     *    "name": "Founders Breakfast Stout",
     *    "brewery": {
     *      "id": 3,
     *      "name": "Founders Brewing Co."
     *    },
     *    "image_url": "https://example.com/beers/founders_breakfast.jpg",
     *    "check_ins_count": 42,
     *    "avg_rating": 4.3
     *  },
     *  "links": {...},
     *  "meta": {...}
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la cerveza solicitada."
     * }
     */
    public function beerActivity(Request $request, $id): JsonResponse
    {
        try {
            $beer = Beer::with('brewery')->findOrFail($id);

            $validated = $request->validate([
                'time_range' => 'nullable|string|in:today,week,month,all',
                'sort' => 'nullable|string|in:recent,popular',
                'per_page' => 'nullable|integer|min:5|max:50',
            ]);

            $timeRange = $validated['time_range'] ?? 'month';
            $sort = $validated['sort'] ?? 'recent';
            $perPage = $validated['per_page'] ?? 10;

            // Construir la consulta base
            $query = CheckIn::where('beer_id', $id)
                ->with(['user:id,name,profile_picture', 'location:id,name'])
                ->withCount(['likes', 'comments']);

            // Aplicar filtro de tiempo
            $startDate = $this->getStartDateFromTimeRange($timeRange);
            if ($startDate) {
                $query->where('check_ins.created_at', '>=', $startDate);
            }

            // Aplicar ordenamiento
            if ($sort === 'popular') {
                $query->orderByDesc('likes_count')
                    ->orderByDesc('comments_count')
                    ->orderByDesc('check_ins.created_at');
            } else {
                $query->orderByDesc('check_ins.created_at');
            }

            // Si el usuario está autenticado, determinar si le ha dado like
            if ($request->user()) {
                $userId = $request->user()->id;
                $query->addSelect([
                    'is_liked' => Like::selectRaw('COUNT(*)')
                        ->whereColumn('check_in_id', 'check_ins.id')
                        ->where('user_id', $userId)
                ]);
            }

            $checkIns = $query->paginate($perPage);

            // Estadísticas de la cerveza
            $stats = CheckIn::where('beer_id', $id)
                ->selectRaw('COUNT(*) as check_ins_count, AVG(rating) as avg_rating')
                ->first();

            return response()->json([
                'data' => CheckInResource::collection($checkIns),
                'beer' => [
                    'id' => $beer->id,
                    'name' => $beer->name,
                    'brewery' => [
                        'id' => $beer->brewery->id,
                        'name' => $beer->brewery->name
                    ],
                    'image_url' => $beer->image_url,
                    'check_ins_count' => $stats->check_ins_count,
                    'avg_rating' => round($stats->avg_rating, 1)
                ],
                'links' => $checkIns->links()->toArray(),
                'meta' => $checkIns->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la cerveza solicitada.'], 404);
        }
    }

    /**
     * Construye la consulta base para los feeds
     */
    private function buildFeedQuery(Request $request, array $validated): \Illuminate\Database\Eloquent\Builder
    {
        $query = CheckIn::with(['user:id,name,profile_picture', 'beer.brewery', 'location:id,name'])
            ->withCount(['likes', 'comments']);

        // Si el usuario está autenticado, determinar si le ha dado like
        if ($request->user()) {
            $userId = $request->user()->id;
            $query->addSelect([
                'is_liked' => Like::selectRaw('COUNT(*)')
                    ->whereColumn('check_in_id', 'check_ins.id')
                    ->where('user_id', $userId)
            ]);
        }

        // Aplicar filtros
        if (!empty($validated['type'])) {
            switch ($validated['type']) {
                case 'beer':
                    // No necesita filtro adicional, todos los check-ins son de cervezas
                    break;
                case 'brewery':
                    if (isset($validated['brewery_id'])) {
                        $query->whereHas('beer', function ($q) use ($validated) {
                            $q->where('brewery_id', $validated['brewery_id']);
                        });
                    }
                    break;
                case 'style':
                    if (isset($validated['style_id'])) {
                        $query->whereHas('beer', function ($q) use ($validated) {
                            $q->where('style_id', $validated['style_id']);
                        });
                    }
                    break;
            }
        }

        // Filtrar por ubicación
        if (!empty($validated['location_id'])) {
            $query->where('location_id', $validated['location_id']);
        }

        // Filtrar por calificación mínima
        if (isset($validated['min_rating'])) {
            $query->where('rating', '>=', $validated['min_rating']);
        }

        // Filtrar por rango de tiempo
        $startDate = $this->getStartDateFromTimeRange($validated['time_range'] ?? 'all');
        if ($startDate) {
            $query->where('check_ins.created_at', '>=', $startDate);
        }

        // Aplicar ordenamiento
        $sort = $validated['sort'] ?? 'recent';
        if ($sort === 'popular') {
            $query->orderByDesc('likes_count')
                ->orderByDesc('comments_count')
                ->orderByDesc('check_ins.created_at');
        } else {
            $query->orderByDesc('check_ins.created_at');
        }

        return $query;
    }

    /**
     * Obtiene la fecha de inicio basada en el rango de tiempo
     */
    private function getStartDateFromTimeRange(?string $timeRange): ?Carbon
    {
        switch ($timeRange) {
            case 'today':
                return Carbon::today();
            case 'week':
                return Carbon::now()->subWeek();
            case 'month':
                return Carbon::now()->subMonth();
            default:
                return null;
        }
    }
}
