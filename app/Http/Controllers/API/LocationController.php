<?php

namespace App\Http\Controllers\API;

use App\Models\Location;
use App\Http\Resources\LocationResource;
use App\Http\Resources\BeerResource;
use App\Http\Resources\CheckInResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Builder;

/**
 * @group Ubicaciones
 *
 * APIs para gestionar ubicaciones donde se pueden encontrar cervezas
 */
class LocationController extends Controller
{
    /**
     * Listar ubicaciones
     *
     * Obtiene un listado paginado de ubicaciones con opciones de filtrado y ordenamiento.
     *
     * @queryParam name string Filtrar por nombre. Example: Beer Garden
     * @queryParam type string Filtrar por tipo (bar, restaurante, tienda, cervecería). Example: bar
     * @queryParam country string Filtrar por país. Example: España
     * @queryParam city string Filtrar por ciudad. Example: Madrid
     * @queryParam sort string Ordenar por: name, type, country, city, created_at. Example: name
     * @queryParam order string Dirección: asc, desc. Example: asc
     * @queryParam per_page int Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": "Cervecería La Cibeles",
     *      "type": "bar",
     *      "address": "Calle de Ponzano, 25, 28003 Madrid",
     *      "city": "Madrid",
     *      "country": "España",
     *      "latitude": 40.439781,
     *      "longitude": -3.694458,
     *      "description": "Bar especializado en cervezas artesanales",
     *      "website": "https://www.cervezaslacibeles.com/",
     *      "phone": "+34912345678",
     *      "opening_hours": "Lun-Jue: 17:00-00:00, Vie-Sáb: 17:00-02:00, Dom: 17:00-22:00",
     *      "image_url": "https://example.com/locations/cibeles.jpg",
     *      "created_at": "2023-04-18T00:00:00.000000Z",
     *      "updated_at": "2023-04-18T00:00:00.000000Z"
     *    }
     *  ],
     *  "links": {...},
     *  "meta": {...}
     * }
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'type' => 'nullable|string|in:bar,restaurante,tienda,cervecería',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'sort' => 'nullable|string|in:name,type,country,city,created_at',
            'order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:5|max:50',
        ]);

        $query = Location::query();

        // Aplicar filtros
        if (!empty($validated['name'])) {
            $query->where('name', 'like', '%' . $validated['name'] . '%');
        }

        if (!empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        if (!empty($validated['country'])) {
            $query->where('country', 'like', '%' . $validated['country'] . '%');
        }

        if (!empty($validated['city'])) {
            $query->where('city', 'like', '%' . $validated['city'] . '%');
        }

        // Ordenar resultados
        $sort = $validated['sort'] ?? 'name';
        $order = $validated['order'] ?? 'asc';

        $query->orderBy($sort, $order);

        $perPage = $validated['per_page'] ?? 10;

        return LocationResource::collection($query->paginate($perPage));
    }

    /**
     * Ver ubicación
     *
     * Muestra información detallada de una ubicación específica.
     *
     * @urlParam id integer required ID de la ubicación. Example: 1
     *
     * @response {
     *  "data": {
     *    "id": 1,
     *    "name": "Cervecería La Cibeles",
     *    "type": "bar",
     *    "address": "Calle de Ponzano, 25, 28003 Madrid",
     *    "city": "Madrid",
     *    "country": "España",
     *    "latitude": 40.439781,
     *    "longitude": -3.694458,
     *    "description": "Bar especializado en cervezas artesanales",
     *    "website": "https://www.cervezaslacibeles.com/",
     *    "phone": "+34912345678",
     *    "opening_hours": "Lun-Jue: 17:00-00:00, Vie-Sáb: 17:00-02:00, Dom: 17:00-22:00",
     *    "image_url": "https://example.com/locations/cibeles.jpg",
     *    "beers_available": [
     *      {
     *        "id": 1,
     *        "name": "La Cibeles Original",
     *        "brewery": {
     *          "id": 5,
     *          "name": "Cerveza La Cibeles"
     *        }
     *      }
     *    ],
     *    "recent_check_ins": [
     *      {
     *        "id": 32,
     *        "user": {
     *          "id": 7,
     *          "name": "María López",
     *          "profile_picture": "https://example.com/avatars/maria.jpg"
     *        },
     *        "beer": {
     *          "id": 1,
     *          "name": "La Cibeles Original"
     *        },
     *        "rating": 4.5,
     *        "created_at": "2023-04-17T19:30:00.000000Z"
     *      }
     *    ],
     *    "created_at": "2023-04-18T00:00:00.000000Z",
     *    "updated_at": "2023-04-18T00:00:00.000000Z"
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la ubicación solicitada."
     * }
     */
    public function show($id): JsonResponse
    {
        try {
            $location = Location::findOrFail($id);

            // Cargar cervezas disponibles y check-ins recientes
            $location->load(['beersAvailable', 'checkIns' => function ($query) {
                $query->with(['user:id,name,profile_picture', 'beer:id,name'])
                    ->latest()
                    ->limit(5);
            }]);

            return response()->json(['data' => new LocationResource($location)]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la ubicación solicitada.'], 404);
        }
    }

    /**
     * Crear ubicación
     *
     * Crea una nueva ubicación en el sistema.
     *
     * @authenticated
     *
     * @bodyParam name string required Nombre de la ubicación. Example: Beer Garden Madrid
     * @bodyParam type string required Tipo de ubicación (bar, restaurante, tienda, cervecería). Example: bar
     * @bodyParam address string required Dirección completa. Example: Calle Gran Vía, 44, 28013 Madrid
     * @bodyParam city string required Ciudad. Example: Madrid
     * @bodyParam country string required País. Example: España
     * @bodyParam latitude numeric required Latitud en grados decimales. Example: 40.420139
     * @bodyParam longitude numeric required Longitud en grados decimales. Example: -3.705224
     * @bodyParam description string Descripción de la ubicación. Example: Terraza cervecera con amplia selección de cervezas artesanales
     * @bodyParam website string URL del sitio web. Example: https://beergarden.es
     * @bodyParam phone string Número de teléfono. Example: +34912345678
     * @bodyParam opening_hours string Horario de apertura. Example: Lun-Dom: 12:00-00:00
     * @bodyParam image_url string URL de la imagen de la ubicación.
     * @bodyParam image file Imagen de la ubicación (JPG, PNG, WebP, máx 2MB).
     *
     * @response 201 {
     *  "data": {
     *    "id": 10,
     *    "name": "Beer Garden Madrid",
     *    "type": "bar",
     *    "address": "Calle Gran Vía, 44, 28013 Madrid",
     *    "city": "Madrid",
     *    "country": "España",
     *    "latitude": 40.420139,
     *    "longitude": -3.705224,
     *    "description": "Terraza cervecera con amplia selección de cervezas artesanales",
     *    "website": "https://beergarden.es",
     *    "phone": "+34912345678",
     *    "opening_hours": "Lun-Dom: 12:00-00:00",
     *    "image_url": "https://example.com/locations/beergarden.jpg"
     *  }
     * }
     *
     * @response 422 {
     *  "message": "Los datos proporcionados no son válidos.",
     *  "errors": {
     *    "name": ["El campo nombre es obligatorio."],
     *    "latitude": ["El campo latitud debe ser un número."]
     *  }
     * }
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|in:bar,restaurante,tienda,cervecería',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'country' => 'required|string|max:100',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'description' => 'nullable|string|max:1000',
                'website' => 'nullable|url|max:255',
                'phone' => 'nullable|string|max:20',
                'opening_hours' => 'nullable|string|max:255',
                'image_url' => 'nullable|url|max:255',
                'image' => 'nullable|file|image|max:2048|mimes:jpg,png,jpeg,webp',
            ]);

            // Si se sube una imagen, procesarla
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $path = $request->file('image')->store('locations', 'public');
                $validated['image_url'] = Storage::url($path);
            }

            $location = Location::create($validated);

            return response()->json(['data' => new LocationResource($location)], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear la ubicación.'], 500);
        }
    }

    /**
     * Actualizar ubicación
     *
     * Actualiza la información de una ubicación existente.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la ubicación. Example: 1
     * @bodyParam name string Nombre de la ubicación. Example: Cervecería La Cibeles Premium
     * @bodyParam type string Tipo de ubicación (bar, restaurante, tienda, cervecería). Example: bar
     * @bodyParam address string Dirección completa. Example: Calle de Ponzano, 28, 28003 Madrid
     * @bodyParam city string Ciudad. Example: Madrid
     * @bodyParam country string País. Example: España
     * @bodyParam latitude numeric Latitud en grados decimales. Example: 40.439800
     * @bodyParam longitude numeric Longitud en grados decimales. Example: -3.694500
     * @bodyParam description string Descripción de la ubicación. Example: Bar premium especializado en cervezas artesanales
     * @bodyParam website string URL del sitio web. Example: https://www.cervezaslacibeles.com/premium
     * @bodyParam phone string Número de teléfono. Example: +34912345679
     * @bodyParam opening_hours string Horario de apertura. Example: Lun-Dom: 17:00-02:00
     * @bodyParam image_url string URL de la imagen de la ubicación.
     * @bodyParam image file Imagen de la ubicación (JPG, PNG, WebP, máx 2MB).
     *
     * @response {
     *  "data": {
     *    "id": 1,
     *    "name": "Cervecería La Cibeles Premium",
     *    "type": "bar",
     *    "address": "Calle de Ponzano, 28, 28003 Madrid",
     *    "city": "Madrid",
     *    "country": "España",
     *    "latitude": 40.439800,
     *    "longitude": -3.694500,
     *    "description": "Bar premium especializado en cervezas artesanales",
     *    "website": "https://www.cervezaslacibeles.com/premium",
     *    "phone": "+34912345679",
     *    "opening_hours": "Lun-Dom: 17:00-02:00",
     *    "image_url": "https://example.com/locations/cibeles_premium.jpg"
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la ubicación solicitada."
     * }
     *
     * @response 422 {
     *  "message": "Los datos proporcionados no son válidos.",
     *  "errors": {
     *    "latitude": ["El campo latitud debe estar entre -90 y 90."]
     *  }
     * }
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $location = Location::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'type' => 'sometimes|required|string|in:bar,restaurante,tienda,cervecería',
                'address' => 'sometimes|required|string|max:255',
                'city' => 'sometimes|required|string|max:100',
                'country' => 'sometimes|required|string|max:100',
                'latitude' => 'sometimes|required|numeric|between:-90,90',
                'longitude' => 'sometimes|required|numeric|between:-180,180',
                'description' => 'nullable|string|max:1000',
                'website' => 'nullable|url|max:255',
                'phone' => 'nullable|string|max:20',
                'opening_hours' => 'nullable|string|max:255',
                'image_url' => 'nullable|url|max:255',
                'image' => 'nullable|file|image|max:2048|mimes:jpg,png,jpeg,webp',
            ]);

            // Si se sube una imagen, procesarla
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Eliminar imagen anterior si existe y está almacenada localmente
                if ($location->image_url && str_starts_with($location->image_url, '/storage/')) {
                    $oldPath = str_replace('/storage/', 'public/', $location->image_url);
                    Storage::delete($oldPath);
                }

                $path = $request->file('image')->store('locations', 'public');
                $validated['image_url'] = Storage::url($path);
            }

            $location->update($validated);

            return response()->json(['data' => new LocationResource($location)]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la ubicación solicitada.'], 404);
        }
    }

    /**
     * Eliminar ubicación
     *
     * Elimina una ubicación del sistema.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la ubicación. Example: 1
     *
     * @response 204 {}
     *
     * @response 404 {
     *  "message": "No se ha encontrado la ubicación solicitada."
     * }
     */
    public function destroy($id): JsonResponse
    {
        try {
            $location = Location::findOrFail($id);

            // Eliminar imagen si existe y está almacenada localmente
            if ($location->image_url && str_starts_with($location->image_url, '/storage/')) {
                $path = str_replace('/storage/', 'public/', $location->image_url);
                Storage::delete($path);
            }

            $location->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la ubicación solicitada.'], 404);
        }
    }

    /**
     * Ubicaciones cercanas
     *
     * Obtiene las ubicaciones cercanas a un punto geográfico específico.
     *
     * @authenticated
     *
     * @queryParam lat numeric required Latitud en grados decimales. Example: 40.416775
     * @queryParam lng numeric required Longitud en grados decimales. Example: -3.703790
     * @queryParam radius numeric Distancia máxima en kilómetros (1-50). Example: 5
     * @queryParam type string Filtrar por tipo (bar, restaurante, tienda, cervecería). Example: bar
     * @queryParam limit integer Número máximo de resultados (1-50). Example: 20
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 3,
     *      "name": "Fábrica Maravillas",
     *      "type": "bar",
     *      "address": "Calle de Valverde, 29, 28004 Madrid",
     *      "city": "Madrid",
     *      "country": "España",
     *      "latitude": 40.423397,
     *      "longitude": -3.701789,
     *      "description": "Brewpub con cervezas artesanales propias",
     *      "website": "https://www.fabricamaravillas.com/",
     *      "phone": "+34915221653",
     *      "opening_hours": "Lun-Dom: 17:00-00:00",
     *      "image_url": "https://example.com/locations/fabrica.jpg",
     *      "distance": 0.74
     *    }
     *  ]
     * }
     *
     * @response 422 {
     *  "message": "Las coordenadas son obligatorias."
     * }
     */
    public function getNearby(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'lat' => 'required|numeric|between:-90,90',
                'lng' => 'required|numeric|between:-180,180',
                'radius' => 'nullable|numeric|min:1|max:50',
                'type' => 'nullable|string|in:bar,restaurante,tienda,cervecería',
                'limit' => 'nullable|integer|min:1|max:50',
            ]);

            $lat = $validated['lat'];
            $lng = $validated['lng'];
            $radius = $validated['radius'] ?? 5; // Default 5km
            $limit = $validated['limit'] ?? 20;

            // Consulta usando la fórmula Haversine para calcular distancias
            $query = Location::selectRaw('*, 
                (6371 * acos(cos(radians(?)) * 
                cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + 
                sin(radians(?)) * 
                sin(radians(latitude)))) AS distance', [$lat, $lng, $lat])
                ->having('distance', '<=', $radius)
                ->orderBy('distance', 'asc')
                ->limit($limit);

            // Filtrar por tipo
            if (!empty($validated['type'])) {
                $query->where('type', $validated['type']);
            }

            $locations = $query->get();

            // Formatear la respuesta incluyendo la distancia
            $formattedLocations = $locations->map(function ($location) {
                $data = new LocationResource($location);
                $resourceData = $data->toArray(request());
                $resourceData['distance'] = round($location->distance, 2);
                return $resourceData;
            });

            return response()->json(['data' => $formattedLocations]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Las coordenadas son obligatorias.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al buscar ubicaciones cercanas.'], 500);
        }
    }

    /**
     * Asignar cerveza a ubicación
     *
     * Añade una cerveza como disponible en una ubicación específica.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la ubicación. Example: 1
     * @bodyParam beer_id integer required ID de la cerveza. Example: 5
     *
     * @response {
     *  "message": "Cerveza añadida correctamente a la ubicación",
     *  "data": {
     *    "id": 5,
     *    "name": "Founders Breakfast Stout",
     *    "brewery": {
     *      "id": 3,
     *      "name": "Founders Brewing Co."
     *    }
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la ubicación o cerveza solicitada."
     * }
     *
     * @response 409 {
     *  "message": "Esta cerveza ya está disponible en esta ubicación."
     * }
     */
    public function addBeer(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'beer_id' => 'required|exists:beers,id',
            ]);

            $location = Location::findOrFail($id);
            $beer_id = $validated['beer_id'];

            // Verificar si ya existe la relación
            if ($location->beersAvailable()->where('beer_id', $beer_id)->exists()) {
                return response()->json([
                    'message' => 'Esta cerveza ya está disponible en esta ubicación.'
                ], 409);
            }

            // Crear la relación
            $location->beersAvailable()->attach($beer_id);

            // Cargar la cerveza con su cervecería
            $beer = \App\Models\Beer::with('brewery:id,name')->find($beer_id);

            return response()->json([
                'message' => 'Cerveza añadida correctamente a la ubicación',
                'data' => new BeerResource($beer)
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la ubicación o cerveza solicitada.'], 404);
        }
    }

    /**
     * Quitar cerveza de ubicación
     *
     * Elimina una cerveza de la lista de disponibles en una ubicación específica.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la ubicación. Example: 1
     * @urlParam beer_id integer required ID de la cerveza. Example: 5
     *
     * @response {
     *  "message": "Cerveza eliminada correctamente de la ubicación"
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la ubicación o cerveza solicitada."
     * }
     */
    public function removeBeer(Request $request, $id, $beer_id): JsonResponse
    {
        try {
            $location = Location::findOrFail($id);

            // Verificar si existe la relación antes de eliminarla
            if (!$location->beersAvailable()->where('beer_id', $beer_id)->exists()) {
                return response()->json([
                    'message' => 'Esta cerveza no está disponible en esta ubicación.'
                ], 404);
            }

            // Eliminar la relación
            $location->beersAvailable()->detach($beer_id);

            return response()->json([
                'message' => 'Cerveza eliminada correctamente de la ubicación'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la ubicación o cerveza solicitada.'], 404);
        }
    }

    /**
     * Cervezas disponibles
     *
     * Obtiene todas las cervezas disponibles en una ubicación específica.
     *
     * @urlParam id integer required ID de la ubicación. Example: 1
     * @queryParam sort string Ordenar por: name, brewery, style, rating. Example: rating
     * @queryParam order string Dirección: asc, desc. Example: desc
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 5,
     *      "name": "Founders Breakfast Stout",
     *      "brewery": {
     *        "id": 3,
     *        "name": "Founders Brewing Co."
     *      },
     *      "style": {
     *        "id": 8,
     *        "name": "Imperial Stout"
     *      },
     *      "abv": 8.3,
     *      "ibu": 60,
     *      "rating_avg": 4.6
     *    }
     *  ],
     *  "location": {
     *    "id": 1,
     *    "name": "Cervecería La Cibeles",
     *    "address": "Calle de Ponzano, 25, 28003 Madrid"
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la ubicación solicitada."
     * }
     */
    public function getBeers(Request $request, $id): JsonResponse
    {
        try {
            $location = Location::findOrFail($id);

            $validated = $request->validate([
                'sort' => 'nullable|string|in:name,brewery,style,rating',
                'order' => 'nullable|string|in:asc,desc',
            ]);

            $sort = $validated['sort'] ?? 'name';
            $order = $validated['order'] ?? 'asc';

            $query = $location->beersAvailable()
                ->with(['brewery:id,name', 'style:id,name'])
                ->withAvg('checkIns', 'rating');

            // Ordenar resultados
            switch ($sort) {
                case 'brewery':
                    $query->join('breweries', 'beers.brewery_id', '=', 'breweries.id')
                        ->orderBy('breweries.name', $order)
                        ->select('beers.*');
                    break;
                case 'style':
                    $query->join('beer_styles', 'beers.style_id', '=', 'beer_styles.id')
                        ->orderBy('beer_styles.name', $order)
                        ->select('beers.*');
                    break;
                case 'rating':
                    $query->orderBy('check_ins_avg_rating', $order);
                    break;
                default:
                    $query->orderBy('beers.name', $order);
            }

            $beers = $query->get();

            return response()->json([
                'data' => BeerResource::collection($beers),
                'location' => [
                    'id' => $location->id,
                    'name' => $location->name,
                    'address' => $location->address
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la ubicación solicitada.'], 404);
        }
    }
}
