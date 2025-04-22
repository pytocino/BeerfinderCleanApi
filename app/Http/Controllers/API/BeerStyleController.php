<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeerResource;
use App\Http\Resources\BeerStyleResource;
use App\Models\Beer;
use App\Models\BeerStyle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

/**
 * @group Estilos de Cerveza
 *
 * APIs para gestionar estilos de cerveza
 */
class BeerStyleController extends Controller
{
    /**
     * Listar estilos de cerveza
     *
     * Obtiene un listado paginado de estilos de cerveza con opciones de filtrado y ordenamiento.
     *
     * @queryParam name string Filtrar por nombre. Example: IPA
     * @queryParam sort string Ordenar por: name, created_at. Example: name
     * @queryParam order string Dirección: asc, desc. Example: asc
     * @queryParam per_page int Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": "IPA (India Pale Ale)",
     *      "description": "Cerveza pálida y lupulada con un amargor característico",
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
            'sort' => 'nullable|string|in:name,created_at',
            'order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:5|max:50',
        ]);

        $query = BeerStyle::query()->withCount('beers');

        // Aplicar filtros
        if (!empty($validated['name'])) {
            $query->where('name', 'like', '%' . $validated['name'] . '%');
        }

        // Ordenar resultados
        $sort = $validated['sort'] ?? 'name';
        $order = $validated['order'] ?? 'asc';

        $query->orderBy($sort, $order);

        $perPage = $validated['per_page'] ?? 10;

        return BeerStyleResource::collection($query->paginate($perPage));
    }

    /**
     * Ver estilo de cerveza
     *
     * Muestra información detallada de un estilo de cerveza específico.
     *
     * @urlParam id integer required ID del estilo de cerveza. Example: 1
     *
     * @response {
     *  "data": {
     *    "id": 1,
     *    "name": "IPA (India Pale Ale)",
     *    "description": "Cerveza pálida y lupulada con un amargor característico",
     *    "beers_count": 12,
     *    "created_at": "2023-04-18T00:00:00.000000Z",
     *    "updated_at": "2023-04-18T00:00:00.000000Z"
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el estilo de cerveza solicitado."
     * }
     */
    public function show($id): JsonResponse
    {
        try {
            $beerStyle = BeerStyle::withCount('beers')->findOrFail($id);
            return response()->json(['data' => new BeerStyleResource($beerStyle)]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se ha encontrado el estilo de cerveza solicitado.'
            ], 404);
        }
    }

    /**
     * Crear estilo de cerveza
     *
     * Crea un nuevo estilo de cerveza en el sistema.
     *
     * @authenticated
     *
     * @bodyParam name string required Nombre del estilo de cerveza. Example: American Pale Ale
     * @bodyParam description string Descripción del estilo de cerveza. Example: Similar a la IPA pero menos amarga
     *
     * @response 201 {
     *  "data": {
     *    "id": 8,
     *    "name": "American Pale Ale",
     *    "description": "Similar a la IPA pero menos amarga",
     *    "beers_count": 0,
     *    "created_at": "2023-04-18T00:00:00.000000Z",
     *    "updated_at": "2023-04-18T00:00:00.000000Z"
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
                'name' => 'required|string|max:100|unique:beer_styles',
                'description' => 'nullable|string|max:1000',
            ]);

            $beerStyle = BeerStyle::create($validated);

            // Cargar el contador de cervezas (siempre será 0 para estilos nuevos)
            $beerStyle->loadCount('beers');

            return response()->json(['data' => new BeerStyleResource($beerStyle)], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear el estilo de cerveza.'], 500);
        }
    }

    /**
     * Actualizar estilo de cerveza
     *
     * Actualiza la información de un estilo de cerveza existente.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del estilo de cerveza. Example: 1
     * @bodyParam name string Nombre del estilo de cerveza. Example: IPA - India Pale Ale
     * @bodyParam description string Descripción del estilo de cerveza. Example: Cerveza con alto contenido en lúpulo
     *
     * @response {
     *  "data": {
     *    "id": 1,
     *    "name": "IPA - India Pale Ale",
     *    "description": "Cerveza con alto contenido en lúpulo",
     *    "beers_count": 12,
     *    "created_at": "2023-04-18T00:00:00.000000Z",
     *    "updated_at": "2023-04-18T00:00:00.000000Z"
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el estilo de cerveza solicitado."
     * }
     *
     * @response 422 {
     *  "message": "Los datos proporcionados no son válidos.",
     *  "errors": {
     *    "name": ["El nombre ya está en uso."]
     *  }
     * }
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $beerStyle = BeerStyle::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:100|unique:beer_styles,name,' . $id,
                'description' => 'nullable|string|max:1000',
            ]);

            $beerStyle->update($validated);
            $beerStyle->loadCount('beers');

            return response()->json(['data' => new BeerStyleResource($beerStyle)]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el estilo de cerveza solicitado.'], 404);
        }
    }

    /**
     * Eliminar estilo de cerveza
     *
     * Elimina un estilo de cerveza del sistema.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del estilo de cerveza. Example: 1
     *
     * @response 204 {}
     *
     * @response 404 {
     *  "message": "No se ha encontrado el estilo de cerveza solicitado."
     * }
     * 
     * @response 409 {
     *  "message": "No se puede eliminar este estilo porque tiene cervezas asociadas."
     * }
     */
    public function destroy($id): JsonResponse
    {
        try {
            $beerStyle = BeerStyle::withCount('beers')->findOrFail($id);

            // Verificar si tiene cervezas asociadas
            if ($beerStyle->beers_count > 0) {
                return response()->json([
                    'message' => 'No se puede eliminar este estilo porque tiene cervezas asociadas.'
                ], 409);
            }

            $beerStyle->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el estilo de cerveza solicitado.'], 404);
        }
    }

    /**
     * Cervezas de un estilo
     *
     * Obtiene todas las cervezas que pertenecen a un estilo específico.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del estilo de cerveza. Example: 1
     * @queryParam sort string Ordenar por: name, abv, ibu, rating, created_at. Example: rating
     * @queryParam order string Dirección: asc, desc. Example: desc
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 3,
     *      "name": "Founders All Day IPA",
     *      "brewery": {
     *        "id": 5,
     *        "name": "Founders Brewing Co."
     *      },
     *      "abv": 4.7,
     *      "ibu": 42,
     *      "description": "Session IPA de sabor intenso pero bajo alcohol",
     *      "image_url": "https://example.com/beers/founders_allday.png",
     *      "rating_avg": 4.2,
     *      "check_ins_count": 87,
     *      "is_favorite": false
     *    }
     *  ],
     *  "links": {...},
     *  "meta": {...},
     *  "style": {
     *    "id": 1,
     *    "name": "IPA (India Pale Ale)",
     *    "description": "Cerveza pálida y lupulada con un amargor característico"
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el estilo de cerveza solicitado."
     * }
     */
    public function getBeers(Request $request, $id): JsonResponse
    {
        try {
            $beerStyle = BeerStyle::findOrFail($id);

            $validated = $request->validate([
                'sort' => 'nullable|string|in:name,abv,ibu,rating,created_at',
                'order' => 'nullable|string|in:asc,desc',
                'per_page' => 'nullable|integer|min:5|max:50',
            ]);

            $sort = $validated['sort'] ?? 'name';
            $order = $validated['order'] ?? 'asc';
            $perPage = $validated['per_page'] ?? 10;

            $query = Beer::where('style_id', $id)
                ->with('brewery')
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
                'style' => [
                    'id' => $beerStyle->id,
                    'name' => $beerStyle->name,
                    'description' => $beerStyle->description,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el estilo de cerveza solicitado.'], 404);
        }
    }

    /**
     * Estilos populares
     * 
     * Obtiene los estilos de cerveza más populares basados en el número de check-ins.
     * 
     * @queryParam limit integer Número de estilos a mostrar (1-20). Example: 5
     * 
     * @response {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "IPA (India Pale Ale)",
     *       "description": "Cerveza pálida y lupulada con un amargor característico",
     *       "beers_count": 12,
     *       "check_ins_count": 358
     *     }
     *   ]
     * }
     */
    public function getPopularStyles(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'limit' => 'nullable|integer|min:1|max:20',
        ]);

        $limit = $validated['limit'] ?? 10;

        // Obtener estilos populares basado en el número de check-ins
        $popularStyles = BeerStyle::withCount(['beers', 'checkIns'])
            ->having('beers_count', '>', 0)
            ->orderByDesc('check_ins_count')
            ->limit($limit)
            ->get()
            ->map(function ($style) {
                return [
                    'id' => $style->id,
                    'name' => $style->name,
                    'description' => $style->description,
                    'beers_count' => $style->beers_count,
                    'check_ins_count' => $style->check_ins_count
                ];
            });

        return response()->json(['data' => $popularStyles]);
    }
}
