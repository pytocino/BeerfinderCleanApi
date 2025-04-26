<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeerResource;
use App\Http\Resources\BreweryResource;
use App\Http\Resources\BeerStyleResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\SearchResource;
use App\Models\Beer;
use App\Models\Brewery;
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
     * Búsqueda global
     * 
     * Permite buscar cervezas, cervecerías, estilos, ubicaciones y usuarios según los criterios especificados.
     * 
     * @queryParam q string Término de búsqueda general. Example: IPA
     * @queryParam type string Tipo de entidad a buscar (beers, breweries, styles, locations, users). Si no se especifica, busca en todas. Example: beers
     * @queryParam country string Filtrar resultados por país (para cervezas, cervecerías y ubicaciones). Example: España
     * @queryParam city string Filtrar resultados por ciudad (para cervecerías y ubicaciones). Example: Madrid
     * @queryParam style_id integer Filtrar cervezas por estilo. Example: 1
     * @queryParam min_rating float Filtrar cervezas por calificación mínima (1.0-5.0). Example: 4.0
     * @queryParam max_distance integer Filtrar ubicaciones por distancia máxima en km (requiere lat y lng). Example: 5
     * @queryParam lat float Latitud para búsqueda por proximidad (para ubicaciones). Example: 40.416775
     * @queryParam lng float Longitud para búsqueda por proximidad (para ubicaciones). Example: -3.703790
     * @queryParam sort string Criterio de ordenamiento (name, rating, distance, created_at). Example: rating
     * @queryParam order string Dirección de ordenamiento (asc, desc). Example: desc
     * @queryParam per_page integer Número de resultados por página. Example: 15
     * 
     * @response {
     *  "beers": {
     *    "data": [
     *      {
     *        "id": 1,
     *        "name": "Mahou Clásica",
     *        "brewery": {
     *          "id": 1,
     *          "name": "Cervecería Mahou"
     *        },
     *        "style": {
     *          "id": 2,
     *          "name": "Lager"
     *        },
     *        "abv": 4.8,
     *        "ibu": 20,
     *        "description": "Cerveza rubia tipo Lager, suave y refrescante.",
     *        "rating_avg": 3.75,
     *        "check_ins_count": 42
     *      }
     *    ],
     *    "total": 1
     *  },
     *  "breweries": {
     *    "data": [],
     *    "total": 0
     *  },
     *  "styles": {
     *    "data": [],
     *    "total": 0
     *  },
     *  "locations": {
     *    "data": [],
     *    "total": 0
     *  },
     *  "users": {
     *    "data": [],
     *    "total": 0
     *  }
     * }
     */
    public function search(Request $request): JsonResponse
    {
        // Validar parámetros de entrada
        $validated = $request->validate([
            'q' => 'nullable|string|min:2|max:100',
            'type' => 'nullable|string|in:beers,breweries,styles,locations,users',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'style_id' => 'nullable|integer|exists:beer_styles,id',
            'min_rating' => 'nullable|numeric|min:1|max:5',
            'max_distance' => 'nullable|integer|min:1|max:100',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'sort' => 'nullable|string|in:name,rating,distance,created_at',
            'order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:5|max:50'
        ]);

        // Establecer valores predeterminados
        $perPage = $validated['per_page'] ?? 10;
        $sort = $validated['sort'] ?? 'name';
        $order = $validated['order'] ?? 'asc';
        $searchTerm = $validated['q'] ?? '';

        // Preparar resultados
        $results = [];

        // Buscar según el tipo especificado o en todos si no se especifica
        $types = $validated['type'] ? [$validated['type']] : ['beers', 'breweries', 'styles', 'locations', 'users'];

        // Buscar en cervezas
        if (in_array('beers', $types)) {
            $beersQuery = Beer::query()
                ->with(['brewery', 'style'])
                ->withAvg('checkIns', 'rating')
                ->withCount('checkIns');

            if (!empty($searchTerm)) {
                $beersQuery->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('description', 'like', "%{$searchTerm}%");
                });
            }

            // Filtros específicos para cervezas
            if (!empty($validated['country'])) {
                $beersQuery->whereHas('brewery', function ($query) use ($validated) {
                    $query->where('country', $validated['country']);
                });
            }

            if (!empty($validated['style_id'])) {
                $beersQuery->where('style_id', $validated['style_id']);
            }

            if (!empty($validated['min_rating'])) {
                $beersQuery->having('check_ins_avg_rating', '>=', $validated['min_rating']);
            }

            // Ordenamiento
            if ($sort === 'rating') {
                $beersQuery->orderBy('check_ins_avg_rating', $order);
            } else if ($sort === 'created_at') {
                $beersQuery->orderBy('created_at', $order);
            } else {
                $beersQuery->orderBy('name', $order);
            }

            $beers = $beersQuery->paginate($perPage);
            $results['beers'] = [
                'data' => $beers->items(),
                'total' => $beers->total()
            ];
        }

        // Buscar en cervecerías
        if (in_array('breweries', $types)) {
            $breweriesQuery = Brewery::query();

            if (!empty($searchTerm)) {
                $breweriesQuery->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('description', 'like', "%{$searchTerm}%");
                });
            }

            // Filtros específicos para cervecerías
            if (!empty($validated['country'])) {
                $breweriesQuery->where('country', $validated['country']);
            }

            if (!empty($validated['city'])) {
                $breweriesQuery->where('city', $validated['city']);
            }

            // Ordenamiento
            if ($sort === 'created_at') {
                $breweriesQuery->orderBy('created_at', $order);
            } else {
                $breweriesQuery->orderBy('name', $order);
            }

            $breweries = $breweriesQuery->paginate($perPage);
            $results['breweries'] = [
                'data' => $breweries->items(),
                'total' => $breweries->total()
            ];
        }

        // Buscar en estilos
        if (in_array('styles', $types)) {
            $stylesQuery = BeerStyle::query();

            if (!empty($searchTerm)) {
                $stylesQuery->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('description', 'like', "%{$searchTerm}%");
                });
            }

            // Ordenamiento
            if ($sort === 'created_at') {
                $stylesQuery->orderBy('created_at', $order);
            } else {
                $stylesQuery->orderBy('name', $order);
            }

            $styles = $stylesQuery->paginate($perPage);
            $results['styles'] = [
                'data' => $styles->items(),
                'total' => $styles->total()
            ];
        }

        // Buscar en ubicaciones
        if (in_array('locations', $types)) {
            $locationsQuery = Location::query();

            if (!empty($searchTerm)) {
                $locationsQuery->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('description', 'like', "%{$searchTerm}%")
                        ->orWhere('address', 'like', "%{$searchTerm}%");
                });
            }

            // Filtros específicos para ubicaciones
            if (!empty($validated['country'])) {
                $locationsQuery->where('country', $validated['country']);
            }

            if (!empty($validated['city'])) {
                $locationsQuery->where('city', $validated['city']);
            }

            // Búsqueda por proximidad si se proporcionan coordenadas
            if (!empty($validated['lat']) && !empty($validated['lng'])) {
                $lat = $validated['lat'];
                $lng = $validated['lng'];
                $maxDistance = $validated['max_distance'] ?? 10; // 10km por defecto

                // Calcular distancia usando la fórmula de Haversine
                $locationsQuery->select('*')
                    ->selectRaw("
                        (6371 * acos(cos(radians(?)) * 
                        cos(radians(latitude)) * cos(radians(longitude) - 
                        radians(?)) + sin(radians(?)) * 
                        sin(radians(latitude)))) AS distance", [$lat, $lng, $lat])
                    ->having('distance', '<=', $maxDistance);

                // Si se pide ordenar por distancia
                if ($sort === 'distance') {
                    $locationsQuery->orderBy('distance', $order);
                }
            } else {
                // Ordenamiento estándar
                if ($sort === 'created_at') {
                    $locationsQuery->orderBy('created_at', $order);
                } else {
                    $locationsQuery->orderBy('name', $order);
                }
            }

            $locations = $locationsQuery->paginate($perPage);
            $results['locations'] = [
                'data' => $locations->items(),
                'total' => $locations->total()
            ];
        }

        // Buscar en usuarios
        if (in_array('users', $types)) {
            $usersQuery = User::query();

            if (!empty($searchTerm)) {
                $usersQuery->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('bio', 'like', "%{$searchTerm}%")
                        ->orWhere('location', 'like', "%{$searchTerm}%");
                });
            }

            // Ordenamiento
            if ($sort === 'created_at') {
                $usersQuery->orderBy('created_at', $order);
            } else {
                $usersQuery->orderBy('name', $order);
            }

            $users = $usersQuery->paginate($perPage);
            $results['users'] = [
                'data' => $users->items(),
                'total' => $users->total()
            ];
        }

        return response()->json(new SearchResource($results));
    }
}
