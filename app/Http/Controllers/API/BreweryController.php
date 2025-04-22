<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BreweryResource;
use App\Http\Resources\BeerResource;
use App\Models\Brewery;
use App\Models\Beer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * @group Cervecerías
 *
 * APIs para gestionar cervecerías
 */
class BreweryController extends Controller
{
    /**
     * Listar cervecerías
     *
     * Obtiene un listado paginado de cervecerías con opciones de filtrado y ordenamiento.
     *
     * @queryParam name string Filtrar por nombre. Example: Mahou
     * @queryParam country string Filtrar por país. Example: España
     * @queryParam city string Filtrar por ciudad. Example: Madrid
     * @queryParam sort string Ordenar por: name, country, city, founded_year, created_at. Example: name
     * @queryParam order string Dirección: asc, desc. Example: asc
     * @queryParam per_page int Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": "Cervecería Mahou",
     *      "country": "España",
     *      "city": "Madrid",
     *      "address": "Calle de Titán, 15, 28045 Madrid",
     *      "founded_year": 1890,
     *      "description": "Una de las cervecerías más antiguas de España",
     *      "website": "https://www.mahou.es",
     *      "logo_url": "https://example.com/logos/mahou.png",
     *      "beers_count": 12,
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
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'sort' => 'nullable|string|in:name,country,city,founded_year,created_at',
            'order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:5|max:50',
        ]);

        $query = Brewery::query()->withCount('beers');

        // Aplicar filtros
        if (!empty($validated['name'])) {
            $query->where('name', 'like', '%' . $validated['name'] . '%');
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

        return BreweryResource::collection($query->paginate($perPage));
    }

    /**
     * Ver cervecería
     *
     * Muestra información detallada de una cervecería específica.
     *
     * @urlParam id integer required ID de la cervecería. Example: 1
     *
     * @response {
     *  "data": {
     *    "id": 1,
     *    "name": "Cervecería Mahou",
     *    "country": "España",
     *    "city": "Madrid",
     *    "address": "Calle de Titán, 15, 28045 Madrid",
     *    "founded_year": 1890,
     *    "description": "Una de las cervecerías más antiguas de España",
     *    "website": "https://www.mahou.es",
     *    "logo_url": "https://example.com/logos/mahou.png",
     *    "beers_count": 12,
     *    "created_at": "2023-04-18T00:00:00.000000Z",
     *    "updated_at": "2023-04-18T00:00:00.000000Z"
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la cervecería solicitada."
     * }
     */
    public function show($id): JsonResponse
    {
        try {
            $brewery = Brewery::withCount('beers')->findOrFail($id);
            return response()->json(['data' => new BreweryResource($brewery)]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la cervecería solicitada.'], 404);
        }
    }

    /**
     * Crear cervecería
     *
     * Crea una nueva cervecería en el sistema.
     *
     * @authenticated
     *
     * @bodyParam name string required Nombre de la cervecería. Example: Guinness
     * @bodyParam country string required País de origen. Example: Irlanda
     * @bodyParam city string Ciudad principal. Example: Dublín
     * @bodyParam address string Dirección física. Example: St. James's Gate, Dublín 8
     * @bodyParam founded_year integer Año de fundación. Example: 1759
     * @bodyParam description string Descripción de la cervecería. Example: Cervecería irlandesa famosa por su stout
     * @bodyParam website string Sitio web oficial. Example: https://www.guinness.com
     * @bodyParam logo_url string URL del logotipo. Example: https://example.com/logos/guinness.png
     * @bodyParam logo file Logo de la cervecería (JPG, PNG, WebP, máx 2MB).
     *
     * @response 201 {
     *  "data": {
     *    "id": 10,
     *    "name": "Guinness",
     *    "country": "Irlanda",
     *    "city": "Dublín",
     *    "address": "St. James's Gate, Dublín 8",
     *    "founded_year": 1759,
     *    "description": "Cervecería irlandesa famosa por su stout",
     *    "website": "https://www.guinness.com",
     *    "logo_url": "https://example.com/logos/guinness.png",
     *    "beers_count": 0
     *  }
     * }
     *
     * @response 422 {
     *  "message": "Los datos proporcionados no son válidos.",
     *  "errors": {
     *    "name": ["El campo nombre es obligatorio."]
     *  }
     * }
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'country' => 'required|string|max:100',
                'city' => 'nullable|string|max:100',
                'address' => 'nullable|string|max:255',
                'founded_year' => 'nullable|integer|min:1000|max:' . date('Y'),
                'description' => 'nullable|string|max:1000',
                'website' => 'nullable|url|max:255',
                'logo_url' => 'nullable|url|max:255',
                'logo' => 'nullable|file|image|max:2048|mimes:jpg,png,jpeg,webp',
            ]);

            // Si se sube un logo, procesarlo
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $path = $request->file('logo')->store('breweries/logos', 'public');
                $validated['logo_url'] = Storage::url($path);
            }

            $brewery = Brewery::create($validated);

            return response()->json(['data' => new BreweryResource($brewery)], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear la cervecería.'], 500);
        }
    }

    /**
     * Actualizar cervecería
     *
     * Actualiza la información de una cervecería existente.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la cervecería. Example: 1
     * @bodyParam name string Nombre de la cervecería. Example: Mahou San Miguel
     * @bodyParam country string País de origen. Example: España
     * @bodyParam city string Ciudad principal. Example: Madrid
     * @bodyParam address string Dirección física. Example: Calle Titán, 15, 28045 Madrid
     * @bodyParam founded_year integer Año de fundación. Example: 1890
     * @bodyParam description string Descripción de la cervecería. Example: Cervecería española con gran tradición
     * @bodyParam website string Sitio web oficial. Example: https://www.mahou-sanmiguel.com
     * @bodyParam logo_url string URL del logotipo.
     * @bodyParam logo file Logo de la cervecería (JPG, PNG, WebP, máx 2MB).
     *
     * @response {
     *  "data": {
     *    "id": 1,
     *    "name": "Mahou San Miguel",
     *    "country": "España",
     *    "city": "Madrid",
     *    "address": "Calle Titán, 15, 28045 Madrid",
     *    "founded_year": 1890,
     *    "description": "Cervecería española con gran tradición",
     *    "website": "https://www.mahou-sanmiguel.com",
     *    "logo_url": "https://example.com/logos/mahou-sanmiguel.png",
     *    "beers_count": 12
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la cervecería solicitada."
     * }
     *
     * @response 422 {
     *  "message": "Los datos proporcionados no son válidos.",
     *  "errors": {
     *    "website": ["La URL del sitio web debe ser una URL válida."]
     *  }
     * }
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $brewery = Brewery::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'country' => 'sometimes|required|string|max:100',
                'city' => 'nullable|string|max:100',
                'address' => 'nullable|string|max:255',
                'founded_year' => 'nullable|integer|min:1000|max:' . date('Y'),
                'description' => 'nullable|string|max:1000',
                'website' => 'nullable|url|max:255',
                'logo_url' => 'nullable|url|max:255',
                'logo' => 'nullable|file|image|max:2048|mimes:jpg,png,jpeg,webp',
            ]);

            // Si se sube un logo, procesarlo
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                // Eliminar logo anterior si existe y está almacenado localmente
                if ($brewery->logo_url && str_starts_with($brewery->logo_url, '/storage/')) {
                    $oldPath = str_replace('/storage/', 'public/', $brewery->logo_url);
                    Storage::delete($oldPath);
                }

                $path = $request->file('logo')->store('breweries/logos', 'public');
                $validated['logo_url'] = Storage::url($path);
            }

            $brewery->update($validated);
            $brewery->loadCount('beers');

            return response()->json(['data' => new BreweryResource($brewery)]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la cervecería solicitada.'], 404);
        }
    }

    /**
     * Eliminar cervecería
     *
     * Elimina una cervecería del sistema.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la cervecería. Example: 1
     *
     * @response 204 {}
     *
     * @response 404 {
     *  "message": "No se ha encontrado la cervecería solicitada."
     * }
     * 
     * @response 409 {
     *  "message": "No se puede eliminar esta cervecería porque tiene cervezas asociadas."
     * }
     */
    public function destroy($id): JsonResponse
    {
        try {
            $brewery = Brewery::withCount('beers')->findOrFail($id);

            // Verificar si tiene cervezas asociadas
            if ($brewery->beers_count > 0) {
                return response()->json([
                    'message' => 'No se puede eliminar esta cervecería porque tiene cervezas asociadas.'
                ], 409);
            }

            // Eliminar logo si existe y está almacenado localmente
            if ($brewery->logo_url && str_starts_with($brewery->logo_url, '/storage/')) {
                $path = str_replace('/storage/', 'public/', $brewery->logo_url);
                Storage::delete($path);
            }

            $brewery->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la cervecería solicitada.'], 404);
        }
    }

    /**
     * Cervezas de la cervecería
     *
     * Obtiene todas las cervezas que pertenecen a una cervecería específica.
     *
     * @urlParam id integer required ID de la cervecería. Example: 1
     * @queryParam sort string Ordenar por: name, abv, ibu, rating, created_at. Example: rating
     * @queryParam order string Dirección: asc, desc. Example: desc
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": "Mahou Clásica",
     *      "style": {
     *        "id": 2,
     *        "name": "Lager"
     *      },
     *      "abv": 4.8,
     *      "ibu": 20,
     *      "description": "Cerveza rubia tipo Lager",
     *      "image_url": "https://example.com/beers/mahou.png",
     *      "rating_avg": 3.75,
     *      "check_ins_count": 42,
     *      "is_favorite": false
     *    }
     *  ],
     *  "links": {...},
     *  "meta": {...},
     *  "brewery": {
     *    "id": 1,
     *    "name": "Cervecería Mahou",
     *    "country": "España",
     *    "logo_url": "https://example.com/logos/mahou.png"
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la cervecería solicitada."
     * }
     */
    public function getBeers(Request $request, $id): JsonResponse
    {
        try {
            $brewery = Brewery::findOrFail($id);

            $validated = $request->validate([
                'sort' => 'nullable|string|in:name,abv,ibu,rating,created_at',
                'order' => 'nullable|string|in:asc,desc',
                'per_page' => 'nullable|integer|min:5|max:50',
            ]);

            $sort = $validated['sort'] ?? 'name';
            $order = $validated['order'] ?? 'asc';
            $perPage = $validated['per_page'] ?? 10;

            $query = Beer::where('brewery_id', $id)
                ->with('style')
                ->withAvg('checkIns', 'rating')
                ->withCount('checkIns');

            // Ordenar resultados
            switch ($sort) {
                case 'rating':
                    $query->orderBy('check_ins_avg_rating', $order);
                    break;
                case 'abv':
                    $query->orderBy('abv', $order);
                    break;
                case 'ibu':
                    $query->orderBy('ibu', $order);
                    break;
                case 'created_at':
                    $query->orderBy('created_at', $order);
                    break;
                default:
                    $query->orderBy('name', $order);
            }

            // Si está autenticado, agregar info si es favorita
            if ($request->user()) {
                $userId = $request->user()->id;
                $query->addSelect([
                    'is_favorite' => \App\Models\Favorite::selectRaw('COUNT(*)')
                        ->whereColumn('beer_id', 'beers.id')
                        ->where('user_id', $userId)
                ]);
            }

            $beers = $query->paginate($perPage);

            return response()->json([
                'data' => BeerResource::collection($beers),
                'links' => $beers->links()->toArray(),
                'meta' => $beers->toArray(),
                'brewery' => [
                    'id' => $brewery->id,
                    'name' => $brewery->name,
                    'country' => $brewery->country,
                    'logo_url' => $brewery->logo_url,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la cervecería solicitada.'], 404);
        }
    }

    /**
     * Obtener top cervecerías
     * 
     * Obtiene las cervecerías mejor valoradas según la calificación media de sus cervezas.
     * 
     * @queryParam limit integer Número de cervecerías a mostrar (1-20). Example: 5
     * @queryParam country string Filtrar por país. Example: España
     * 
     * @response {
     *   "data": [
     *     {
     *       "id": 3,
     *       "name": "Founders Brewing Co.",
     *       "country": "Estados Unidos",
     *       "city": "Grand Rapids",
     *       "logo_url": "https://example.com/logos/founders.png",
     *       "beers_count": 8,
     *       "avg_rating": 4.35
     *     }
     *   ]
     * }
     */
    public function getTopBreweries(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'limit' => 'nullable|integer|min:1|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $limit = $validated['limit'] ?? 10;

        // Consulta para obtener las cervecerías con sus calificaciones medias
        $query = Brewery::withCount('beers')
            ->join('beers', 'breweries.id', '=', 'beers.brewery_id')
            ->leftJoin('check_ins', 'beers.id', '=', 'check_ins.beer_id')
            ->select('breweries.*')
            ->selectRaw('AVG(check_ins.rating) as avg_rating')
            ->groupBy('breweries.id')
            ->having('beers_count', '>', 0)
            ->having('avg_rating', '>', 0)
            ->orderByDesc('avg_rating');

        if (!empty($validated['country'])) {
            $query->where('breweries.country', $validated['country']);
        }

        $breweries = $query->limit($limit)->get();

        // Transformar los resultados
        $formattedBreweries = $breweries->map(function ($brewery) {
            return [
                'id' => $brewery->id,
                'name' => $brewery->name,
                'country' => $brewery->country,
                'city' => $brewery->city,
                'logo_url' => $brewery->logo_url,
                'beers_count' => $brewery->beers_count,
                'avg_rating' => round($brewery->avg_rating, 2)
            ];
        });

        return response()->json(['data' => $formattedBreweries]);
    }
}
