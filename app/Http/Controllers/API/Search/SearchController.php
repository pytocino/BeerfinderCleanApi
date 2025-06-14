<?php

namespace App\Http\Controllers\API\Search;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchResource;
use App\Models\Beer;
use App\Models\BeerStyle;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Búsqueda
 * 
 * APIs para buscar diferentes entidades en el sistema
 */
class SearchController extends Controller
{
    /**
     * Búsqueda global con límites dinámicos
     * 
     * Permite buscar cervezas, cervecerías, estilos, ubicaciones y usuarios según los criterios especificados.
     * 
     * LÍMITES DINÁMICOS:
     * - 1 carácter: Máximo 10 resultados, búsqueda solo al inicio de nombres
     * - 2 caracteres: Máximo 20 resultados, búsqueda al inicio de nombres  
     * - 3+ caracteres: Sin límites adicionales, búsqueda en cualquier parte
     * 
     * @queryParam q string Término de búsqueda general (mínimo 1 carácter). Example: I
     * @queryParam type string Tipo de entidad a buscar (beers, breweries, styles, locations, users). Si no se especifica, busca en todas. Example: beers
     * @queryParam country string Filtrar resultados por país (para cervezas, cervecerías, ubicaciones). Example: España
     * @queryParam city string Filtrar resultados por ciudad (para cervecerías, ubicaciones y cervezas). Example: Madrid
     * @queryParam style_id integer Filtrar cervezas por estilo. Example: 1
     * @queryParam abv_min float Filtrar cervezas por graduación alcohólica mínima. Example: 4.5
     * @queryParam abv_max float Filtrar cervezas por graduación alcohólica máxima. Example: 7.0
     * @queryParam ibu_min integer Filtrar cervezas por IBU mínimo. Example: 20
     * @queryParam ibu_max integer Filtrar cervezas por IBU máximo. Example: 50
     * @queryParam origin_country string Filtrar estilos por país de origen. Example: Alemania
     * @queryParam max_distance integer Filtrar ubicaciones por distancia máxima en km (requiere lat y lng). Example: 5
     * @queryParam lat float Latitud para búsqueda por proximidad (para ubicaciones). Example: 40.416775
     * @queryParam lng float Longitud para búsqueda por proximidad (para ubicaciones). Example: -3.703790
     * @queryParam sort string Criterio de ordenamiento (name, distance, created_at). Example: name
     * @queryParam order string Dirección de ordenamiento (asc, desc). Example: desc
     * @queryParam per_page integer Número de resultados por página. Example: 15
     * 
     * @response {
     *   "beers": {
     *     "data": [
     *       {
     *         "id": 13,
     *         "name": "ipsa Beer",
     *         "description": "Quam consequatur rerum voluptatem voluptate dolor. Aut odit veritatis et qui velit eos aliquam. Quia totam qui quod modi pariatur consequatur. Vel cum pariatur ducimus assumenda quisquam repellat et qui.",
     *         "brewery": {
     *           "id": 4,
     *           "name": "Feeney-Cole Brewery",
     *           "description": "Eum ullam ex odio saepe.",
     *           "country": "Costa Rica",
     *           "city": "Rueckerside",
     *           "full_location": "Rueckerside, Costa Rica",
     *           "image_url": "https://via.placeholder.com/640x480.png/003311?text=business+illo",
     *           "website": null,
     *           "has_location": true,
     *           "has_contact_info": false,
     *           "created_at": "2025-05-05T22:28:50.000000Z",
     *           "updated_at": "2025-05-05T22:28:50.000000Z"
     *         },
     *         "style": {
     *           "id": 7,
     *           "name": "omnis Style",
     *           "description": "Reprehenderit laudantium pariatur deleniti et optio.",
     *           "short_description": "Reprehenderit laudantium pariatur deleniti et optio.",
     *           "origin_country": "Montserrat",
     *           "created_at": "2025-05-05T22:28:50.000000Z",
     *           "updated_at": "2025-05-05T22:28:50.000000Z"
     *         },
     *         "abv": 4.59,
     *         "ibu": 118,
     *         "image_url": "http://localhost:8000/images/default-beer.png",
     *         "ratings_count": 0,
     *         "bitterness_level": "Muy amargo",
     *         "alcohol_level": "Medio",
     *         "is_favorited": false,
     *         "created_at": "2025-05-05T22:28:50.000000Z",
     *         "updated_at": "2025-05-05T22:28:50.000000Z"
     *       }
     *     ],
     *     "total": 5
     *   },
     *   "styles": {
     *     "data": [
     *       {
     *         "id": 1,
     *         "name": "repellendus Style",
     *         "description": null,
     *         "short_description": "",
     *         "origin_country": "Cuba",
     *         "created_at": "2025-05-05T22:28:50.000000Z",
     *         "updated_at": "2025-05-05T22:28:50.000000Z"
     *       }
     *     ],
     *     "total": 5
     *   },
     *   "locations": {
     *     "data": [
     *       {
     *         "id": 1,
     *         "name": "Stracke-Balistreri Quis",
     *         "type": "other",
     *         "description": null,
     *         "status": "active",
     *         "opening_hours": {"friday":[{"open":"12:00","close":"23:00"}], ...},
     *         "address": "8606 Schmeler Rapid Apt. 096",
     *         "city": "Port Dixie",
     *         "country": "Reunion",
     *         "latitude": -49.428926,
     *         "longitude": 125.257071,
     *         "image_url": "https://via.placeholder.com/640x480.png/002200?text=bars+ullam",
     *         "cover_photo_url": "http://localhost:8000/images/default-cover.png",
     *         "website": "http://glover.info/sit-ab-sed-necessitatibus-asperiores-enim",
     *         "email": "ruthie.willms@blick.com",
     *         "phone": "985-959-9022",
     *         "verified": false,
     *         "is_active": true,
     *         "is_open_now": false,
     *         "today_hours": [{"open":"12:00","close":"23:00"}],
     *         "full_address": "8606 Schmeler Rapid Apt. 096, Port Dixie, Reunion",
     *         "created_at": "2025-05-05T22:28:51.000000Z",
     *         "updated_at": "2025-05-05T22:28:51.000000Z"
     *       }
     *     ],
     *     "total": 5
     *   },
     *   "users": {
     *     "data": [
     *       {
     *         "id": 2,
     *         "name": "Walter Kuhn",
     *         "username": "jesus.schmitt",
     *         "profile_picture": "https://via.placeholder.com/200x200.png/008844?text=people+dolorum",
     *         "is_admin": false,
     *         "private_profile": false,
     *         "status": "active",
     *         "last_active_at": "2025-05-05T22:28:49.000000Z",
     *         "is_me": false,
     *         "created_at": "2025-05-05T22:28:50.000000Z",
     *         "updated_at": "2025-05-05T22:28:50.000000Z"
     *       }
     *     ],
     *     "total": 5
     *   }
     * }
     */
    public function search(Request $request): JsonResponse
    {
        // Validar parámetros de entrada
        $validated = $request->validate([
            'q' => 'nullable|string|min:1|max:100', // Cambiado: permite búsquedas desde 1 carácter
            'type' => 'nullable|string|in:beers,breweries,styles,locations,users',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'style_id' => 'nullable|integer|exists:beer_styles,id',
            'min_rating' => 'nullable|numeric|min:1|max:5',
            'abv_min' => 'nullable|numeric|min:0',
            'abv_max' => 'nullable|numeric|min:0',
            'ibu_min' => 'nullable|integer|min:0',
            'ibu_max' => 'nullable|integer|min:0',
            'origin_country' => 'nullable|string|max:100',
            'max_distance' => 'nullable|integer|min:1|max:100',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'sort' => 'nullable|string|in:name,rating,distance,created_at',
            'order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:5|max:50'
        ]);

        // Establecer valores predeterminados y límites dinámicos
        $searchTerm = $validated['q'] ?? '';
        $searchLength = strlen($searchTerm);
        
        // Límites dinámicos basados en la longitud del término de búsqueda
        if ($searchLength === 1) {
            $perPage = min($validated['per_page'] ?? 5, 10); // Máximo 10 resultados con 1 carácter
            $maxResults = 10;
        } elseif ($searchLength === 2) {
            $perPage = min($validated['per_page'] ?? 10, 20); // Máximo 20 resultados con 2 caracteres
            $maxResults = 20;
        } else {
            $perPage = $validated['per_page'] ?? 10; // Sin restricción adicional para 3+ caracteres
            $maxResults = null;
        }
        
        $sort = $validated['sort'] ?? 'name';
        $order = $validated['order'] ?? 'asc';

        // Si no hay parámetros de búsqueda, mostrar los 5 registros más interesantes de cada entidad
        $defaultLimit = 5;
        $isDefault = empty($searchTerm) && (empty($validated['type'] ?? null)) && empty($validated['country']) && empty($validated['city']) && empty($validated['style_id']) && empty($validated['abv_min']) && empty($validated['abv_max']) && empty($validated['ibu_min']) && empty($validated['ibu_max']) && empty($validated['origin_country']) && empty($validated['lat']) && empty($validated['lng']);

        $results = [];
        $types = !empty($validated['type']) ? [$validated['type']] : ['beers', 'breweries', 'styles', 'locations', 'users'];

        // Cervezas
        if (in_array('beers', $types)) {
            $beersQuery = Beer::query()->with(['brewery', 'style']);
            if (!$isDefault) {
                if (!empty($searchTerm)) {
                    $beersQuery->where(function ($query) use ($searchTerm, $searchLength) {
                        if ($searchLength === 1) {
                            // Con 1 carácter: buscar solo al inicio del nombre para mayor precisión
                            $query->where('name', 'like', "{$searchTerm}%")
                                ->orWhere('description', 'like', "{$searchTerm}%");
                        } else {
                            // Con 2+ caracteres: búsqueda normal en cualquier parte
                            $query->where('name', 'like', "%{$searchTerm}%")
                                ->orWhere('description', 'like', "%{$searchTerm}%");
                        }
                    });
                }
                if (!empty($validated['country'])) {
                    $beersQuery->whereHas('brewery', function ($query) use ($validated) {
                        $query->where('country', '=', $validated['country']);
                    });
                }
                if (!empty($validated['style_id'])) {
                    $beersQuery->where('style_id', '=', $validated['style_id']);
                }
                if (!empty($validated['abv_min'])) {
                    $beersQuery->where('abv', '>=', $validated['abv_min']);
                }
                if (!empty($validated['abv_max'])) {
                    $beersQuery->where('abv', '<=', $validated['abv_max']);
                }
                if (!empty($validated['ibu_min'])) {
                    $beersQuery->where('ibu', '>=', $validated['ibu_min']);
                }
                if (!empty($validated['ibu_max'])) {
                    $beersQuery->where('ibu', '<=', $validated['ibu_max']);
                }
                if ($sort === 'created_at') {
                    $beersQuery->orderBy('created_at', $order);
                } else {
                    $beersQuery->orderBy('name', $order);
                }
                
                // Aplicar límite dinámico si es necesario
                if ($maxResults && $searchLength <= 2) {
                    $beers = $beersQuery->limit($maxResults)->get();
                    $total = $beers->count();
                    $results['beers'] = [
                        'data' => $beers,
                        'total' => $total
                    ];
                } else {
                    $beers = $beersQuery->paginate($perPage);
                    $results['beers'] = [
                        'data' => $beers->items(),
                        'total' => $beers->total()
                    ];
                }
            } else {
                // Por defecto: mostrar las cervezas más recientes
                $beers = $beersQuery->orderBy('created_at', 'desc')->limit($defaultLimit)->get();
                $results['beers'] = [
                    'data' => $beers,
                    'total' => $beers->count()
                ];
            }
        }

        // Cervecerías
        if (in_array('breweries', $types)) {
            $breweriesQuery = \App\Models\Brewery::query();
            if (!$isDefault) {
                if (!empty($searchTerm)) {
                    $breweriesQuery->where(function ($query) use ($searchTerm, $searchLength) {
                        if ($searchLength === 1) {
                            // Con 1 carácter: buscar solo al inicio del nombre
                            $query->where('name', 'like', "{$searchTerm}%")
                                ->orWhere('description', 'like', "{$searchTerm}%");
                        } else {
                            // Con 2+ caracteres: búsqueda normal
                            $query->where('name', 'like', "%{$searchTerm}%")
                                ->orWhere('description', 'like', "%{$searchTerm}%");
                        }
                    });
                }
                if (!empty($validated['country'])) {
                    $breweriesQuery->where('country', '=', $validated['country']);
                }
                if (!empty($validated['city'])) {
                    $breweriesQuery->where('city', '=', $validated['city']);
                }
                if ($sort === 'created_at') {
                    $breweriesQuery->orderBy('created_at', $order);
                } else {
                    $breweriesQuery->orderBy('name', $order);
                }
                
                // Aplicar límite dinámico si es necesario
                if ($maxResults && $searchLength <= 2) {
                    $breweries = $breweriesQuery->limit($maxResults)->get();
                    $total = $breweries->count();
                    $results['breweries'] = [
                        'data' => $breweries,
                        'total' => $total
                    ];
                } else {
                    $breweries = $breweriesQuery->paginate($perPage);
                    $results['breweries'] = [
                        'data' => $breweries->items(),
                        'total' => $breweries->total()
                    ];
                }
            } else {
                // Por defecto: mostrar las cervecerías más recientes
                $breweries = $breweriesQuery->orderBy('created_at', 'desc')->limit($defaultLimit)->get();
                $results['breweries'] = [
                    'data' => $breweries,
                    'total' => $breweries->count()
                ];
            }
        }

        // Estilos
        if (in_array('styles', $types)) {
            $stylesQuery = BeerStyle::query();
            if (!$isDefault) {
                if (!empty($searchTerm)) {
                    $stylesQuery->where(function ($query) use ($searchTerm, $searchLength) {
                        if ($searchLength === 1) {
                            // Con 1 carácter: buscar solo al inicio del nombre
                            $query->where('name', 'like', "{$searchTerm}%")
                                ->orWhere('description', 'like', "{$searchTerm}%");
                        } else {
                            // Con 2+ caracteres: búsqueda normal
                            $query->where('name', 'like', "%{$searchTerm}%")
                                ->orWhere('description', 'like', "%{$searchTerm}%");
                        }
                    });
                }
                if (!empty($validated['origin_country'])) {
                    $stylesQuery->where('origin_country', '=', $validated['origin_country']);
                }
                if ($sort === 'created_at') {
                    $stylesQuery->orderBy('created_at', $order);
                } else {
                    $stylesQuery->orderBy('name', $order);
                }
                
                // Aplicar límite dinámico si es necesario
                if ($maxResults && $searchLength <= 2) {
                    $styles = $stylesQuery->limit($maxResults)->get();
                    $total = $styles->count();
                    $results['styles'] = [
                        'data' => $styles,
                        'total' => $total
                    ];
                } else {
                    $styles = $stylesQuery->paginate($perPage);
                    $results['styles'] = [
                        'data' => $styles->items(),
                        'total' => $styles->total()
                    ];
                }
            } else {
                // Por defecto: mostrar los estilos más recientes
                $styles = $stylesQuery->orderBy('created_at', 'desc')->limit($defaultLimit)->get();
                $results['styles'] = [
                    'data' => $styles,
                    'total' => $styles->count()
                ];
            }
        }

        // Ubicaciones
        if (in_array('locations', $types)) {
            $locationsQuery = Location::query();
            if (!$isDefault) {
                if (!empty($searchTerm)) {
                    $locationsQuery->where(function ($query) use ($searchTerm, $searchLength) {
                        if ($searchLength === 1) {
                            // Con 1 carácter: buscar solo al inicio del nombre
                            $query->where('name', 'like', "{$searchTerm}%")
                                ->orWhere('description', 'like', "{$searchTerm}%")
                                ->orWhere('address', 'like', "{$searchTerm}%");
                        } else {
                            // Con 2+ caracteres: búsqueda normal
                            $query->where('name', 'like', "%{$searchTerm}%")
                                ->orWhere('description', 'like', "%{$searchTerm}%")
                                ->orWhere('address', 'like', "%{$searchTerm}%");
                        }
                    });
                }
                if (!empty($validated['country'])) {
                    $locationsQuery->where('country', '=', $validated['country']);
                }
                if (!empty($validated['city'])) {
                    $locationsQuery->where('city', '=', $validated['city']);
                }
                if (!empty($validated['lat']) && !empty($validated['lng'])) {
                    $lat = $validated['lat'];
                    $lng = $validated['lng'];
                    $maxDistance = $validated['max_distance'] ?? 10;
                    $locationsQuery->select('*')
                        ->selectRaw("
                            (6371 * acos(cos(radians(?)) * 
                            cos(radians(latitude)) * cos(radians(longitude) - 
                            radians(?)) + sin(radians(?)) * 
                            sin(radians(latitude)))) AS distance", [$lat, $lng, $lat])
                        ->having('distance', '<=', $maxDistance);
                    if ($sort === 'distance') {
                        $locationsQuery->orderBy('distance', $order);
                    }
                } else {
                    if ($sort === 'created_at') {
                        $locationsQuery->orderBy('created_at', $order);
                    } else {
                        $locationsQuery->orderBy('name', $order);
                    }
                }
                
                // Aplicar límite dinámico si es necesario
                if ($maxResults && $searchLength <= 2) {
                    $locations = $locationsQuery->limit($maxResults)->get();
                    $total = $locations->count();
                    $results['locations'] = [
                        'data' => $locations,
                        'total' => $total
                    ];
                } else {
                    $locations = $locationsQuery->paginate($perPage);
                    $results['locations'] = [
                        'data' => $locations->items(),
                        'total' => $locations->total()
                    ];
                }
            } else {
                // Por defecto: mostrar las ubicaciones más recientes
                $locations = $locationsQuery->orderBy('created_at', 'desc')->limit($defaultLimit)->get();
                $results['locations'] = [
                    'data' => $locations,
                    'total' => $locations->count()
                ];
            }
        }

        // Usuarios
        if (in_array('users', $types)) {
            $usersQuery = User::query();
            if (!$isDefault) {
                if (!empty($searchTerm)) {
                    $usersQuery->where(function ($query) use ($searchTerm, $searchLength) {
                        if ($searchLength === 1) {
                            // Con 1 carácter: buscar solo al inicio del nombre o username
                            $query->where('name', 'like', "{$searchTerm}%")
                                ->orWhere('username', 'like', "{$searchTerm}%");
                        } else {
                            // Con 2+ caracteres: búsqueda normal
                            $query->where('name', 'like', "%{$searchTerm}%")
                                ->orWhere('username', 'like', "%{$searchTerm}%");
                        }
                    });
                }
                if ($sort === 'created_at') {
                    $usersQuery->orderBy('created_at', $order);
                } else {
                    $usersQuery->orderBy('name', $order);
                }
                
                // Aplicar límite dinámico si es necesario
                if ($maxResults && $searchLength <= 2) {
                    $users = $usersQuery->limit($maxResults)->get();
                    $total = $users->count();
                    $results['users'] = [
                        'data' => $users,
                        'total' => $total
                    ];
                } else {
                    $users = $usersQuery->paginate($perPage);
                    $results['users'] = [
                        'data' => $users->items(),
                        'total' => $users->total()
                    ];
                }
            } else {
                // Por defecto: mostrar los usuarios más recientes
                $users = $usersQuery->orderBy('created_at', 'desc')->limit($defaultLimit)->get();
                $results['users'] = [
                    'data' => $users,
                    'total' => $users->count()
                ];
            }
        }

        return response()->json(new SearchResource($results));
    }
}
