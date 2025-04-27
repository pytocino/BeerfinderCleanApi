<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeerResource;
use App\Http\Resources\CheckInResource;
use App\Models\Beer;
use App\Models\Favorite;
use Illuminate\Container\Attributes\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * @group Cervezas
 *
 * APIs para gestionar cervezas
 */
class BeerController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'brewery_id' => 'nullable|integer|exists:breweries,id',
            'style_id' => 'nullable|integer|exists:beer_styles,id',
            'min_abv' => 'nullable|numeric|min:0|max:100',
            'max_abv' => 'nullable|numeric|min:0|max:100',
            'min_ibu' => 'nullable|integer|min:0|max:200',
            'max_ibu' => 'nullable|integer|min:0|max:200',
            'min_rating' => 'nullable|numeric|min:1|max:5',
            'sort' => 'nullable|string|in:name,abv,ibu,rating,created_at',
            'order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:5|max:50',
        ]);

        $query = Beer::query()
            ->with(['brewery', 'style'])
            ->withAvg('checkIns', 'rating')
            ->withCount('checkIns');

        // Aplicar filtros
        if (!empty($validated['name'])) {
            $query->where('name', 'like', '%' . $validated['name'] . '%');
        }

        if (!empty($validated['brewery_id'])) {
            $query->where('brewery_id', $validated['brewery_id']);
        }

        if (!empty($validated['style_id'])) {
            $query->where('style_id', $validated['style_id']);
        }

        if (!empty($validated['min_abv'])) {
            $query->where('abv', '>=', $validated['min_abv']);
        }

        if (!empty($validated['max_abv'])) {
            $query->where('abv', '<=', $validated['max_abv']);
        }

        if (!empty($validated['min_ibu'])) {
            $query->where('ibu', '>=', $validated['min_ibu']);
        }

        if (!empty($validated['max_ibu'])) {
            $query->where('ibu', '<=', $validated['max_ibu']);
        }

        if (!empty($validated['min_rating'])) {
            $query->having('check_ins_avg_rating', '>=', $validated['min_rating']);
        }

        // Ordenar resultados
        $sort = $validated['sort'] ?? 'name';
        $order = $validated['order'] ?? 'asc';

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
                'is_favorite' => Favorite::selectRaw('COUNT(*)')
                    ->whereColumn('beer_id', 'beers.id')
                    ->where('user_id', $userId)
            ]);
        }

        $perPage = $validated['per_page'] ?? 10;

        return BeerResource::collection($query->paginate($perPage));
    }

    /**
     * Ver cerveza
     *
     * Muestra información detallada de una cerveza específica.
     *
     * @urlParam id integer required ID de la cerveza. Example: 1
     *
     * @response {
     *  "data": {
     *    "id": 1,
     *    "name": "Mahou Clásica",
     *    "brewery": {
     *      "id": 1,
     *      "name": "Cervecería Mahou",
     *      "country": "España",
     *      "logo_url": "https://example.com/logos/mahou.png"
     *    },
     *    "style": {
     *      "id": 2,
     *      "name": "Lager"
     *    },
     *    "abv": 4.8,
     *    "ibu": 20,
     *    "description": "Cerveza rubia tipo Lager",
     *    "image_url": "https://example.com/beers/mahou.png",
     *    "rating_avg": 3.75,
     *    "check_ins_count": 42,
     *    "is_favorite": false
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la cerveza solicitada."
     * }
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $beer = Beer::findOrFail($id);

            $beer->load(['brewery', 'style']);

            // Si está autenticado, agregar info si es favorita
            if ($request->user()) {
                $beer->is_favorite = Favorite::where('beer_id', $beer->id)
                    ->where('user_id', $request->user()->id)
                    ->exists();
            }

            return response()->json(['data' => new BeerResource($beer)]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la cerveza solicitada.'], 404);
        }
    }

    /**
     * Crear cerveza
     *
     * Crea una nueva cerveza en el sistema.
     *
     * @authenticated
     *
     * @bodyParam name string required Nombre de la cerveza. Example: Estrella Galicia Especial
     * @bodyParam brewery_id integer required ID de la cervecería. Example: 2
     * @bodyParam style_id integer required ID del estilo. Example: 2
     * @bodyParam abv numeric ABV (Alcohol By Volume) en %. Example: 5.5
     * @bodyParam ibu integer IBU (International Bitterness Units). Example: 25
     * @bodyParam description string Descripción de la cerveza. Example: Cerveza premium con carácter atlántico
     * @bodyParam image_url string URL de la imagen de la cerveza. Example: https://example.com/beers/estrella.png
     * @bodyParam image file Imagen de la cerveza (JPG, PNG, WebP, máx 2MB).
     *
     * @response 201 {
     *  "data": {
     *    "id": 51,
     *    "name": "Estrella Galicia Especial",
     *    "brewery": {
     *      "id": 2,
     *      "name": "Estrella Galicia"
     *    },
     *    "style": {
     *      "id": 2,
     *      "name": "Lager"
     *    },
     *    "abv": 5.5,
     *    "ibu": 25,
     *    "description": "Cerveza premium con carácter atlántico",
     *    "image_url": "https://example.com/beers/estrella.png"
     *  }
     * }
     *
     * @response 422 {
     *  "message": "Los datos proporcionados no son válidos.",
     *  "errors": {
     *    "name": ["El campo nombre es obligatorio."],
     *    "brewery_id": ["La cervecería seleccionada no existe."]
     *  }
     * }
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'brewery_id' => 'required|exists:breweries,id',
                'style_id' => 'required|exists:beer_styles,id',
                'abv' => 'nullable|numeric|between:0,100',
                'ibu' => 'nullable|integer|between:0,200',
                'description' => 'nullable|string|max:1000',
                'image_url' => 'nullable|url|max:255',
                'image' => 'nullable|file|image|max:2048|mimes:jpg,png,jpeg,webp',
            ]);

            // Si se sube una imagen, procesarla
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $path = $request->file('image')->store('beers', 'public');
                $validated['image_url'] = Storage::url($path);
            }

            $beer = Beer::create($validated);
            $beer->load(['brewery', 'style']);

            return response()->json(['data' => new BeerResource($beer)], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear la cerveza.'], 500);
        }
    }

    /**
     * Actualizar cerveza
     *
     * Actualiza la información de una cerveza existente.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la cerveza. Example: 1
     * @bodyParam name string Nombre de la cerveza. Example: Mahou Clásica Edición Especial
     * @bodyParam brewery_id integer ID de la cervecería. Example: 1
     * @bodyParam style_id integer ID del estilo. Example: 2
     * @bodyParam abv numeric ABV (Alcohol By Volume) en %. Example: 4.9
     * @bodyParam ibu integer IBU (International Bitterness Units). Example: 22
     * @bodyParam description string Descripción de la cerveza. Example: Versión mejorada de la clásica Mahou
     * @bodyParam image_url string URL de la imagen de la cerveza.
     * @bodyParam image file Imagen de la cerveza (JPG, PNG, WebP, máx 2MB).
     *
     * @response {
     *  "data": {
     *    "id": 1,
     *    "name": "Mahou Clásica Edición Especial",
     *    "brewery": {
     *      "id": 1,
     *      "name": "Cervecería Mahou"
     *    },
     *    "style": {
     *      "id": 2,
     *      "name": "Lager"
     *    },
     *    "abv": 4.9,
     *    "ibu": 22,
     *    "description": "Versión mejorada de la clásica Mahou",
     *    "image_url": "https://example.com/beers/mahou_especial.png"
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la cerveza solicitada."
     * }
     *
     * @response 422 {
     *  "message": "Los datos proporcionados no son válidos.",
     *  "errors": {
     *    "name": ["El nombre no puede superar los 255 caracteres."]
     *  }
     * }
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $beer = Beer::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'brewery_id' => 'sometimes|required|exists:breweries,id',
                'style_id' => 'sometimes|required|exists:beer_styles,id',
                'abv' => 'nullable|numeric|between:0,100',
                'ibu' => 'nullable|integer|between:0,200',
                'description' => 'nullable|string|max:1000',
                'image_url' => 'nullable|url|max:255',
                'image' => 'nullable|file|image|max:2048|mimes:jpg,png,jpeg,webp',
            ]);

            // Si se sube una imagen, procesarla
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Eliminar imagen anterior si existe y es una imagen almacenada localmente
                if ($beer->image_url && str_starts_with($beer->image_url, '/storage/')) {
                    $oldPath = str_replace('/storage/', 'public/', $beer->image_url);
                    Storage::delete($oldPath);
                }

                $path = $request->file('image')->store('beers', 'public');
                $validated['image_url'] = Storage::url($path);
            }

            $beer->update($validated);
            $beer->load(['brewery', 'style']);

            return response()->json(['data' => new BeerResource($beer)]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la cerveza solicitada.'], 404);
        }
    }

    /**
     * Eliminar cerveza
     *
     * Elimina una cerveza del sistema.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la cerveza. Example: 1
     *
     * @response 204 {}
     *
     * @response 404 {
     *  "message": "No se ha encontrado la cerveza solicitada."
     * }
     */
    public function destroy($id): JsonResponse
    {
        try {
            $beer = Beer::findOrFail($id);

            // Eliminar imagen si existe y es una imagen almacenada localmente
            if ($beer->image_url && str_starts_with($beer->image_url, '/storage/')) {
                $path = str_replace('/storage/', 'public/', $beer->image_url);
                Storage::delete($path);
            }

            $beer->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la cerveza solicitada.'], 404);
        }
    }

    /**
     * Marcar como favorita
     *
     * Marca una cerveza como favorita para el usuario autenticado.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la cerveza. Example: 1
     *
     * @response {
     *  "message": "Cerveza añadida a favoritos"
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la cerveza solicitada."
     * }
     */
    public function favorite(Request $request, $id): JsonResponse
    {
        try {
            $beer = Beer::findOrFail($id);

            Favorite::firstOrCreate([
                'user_id' => $request->user()->id,
                'beer_id' => $beer->id,
            ]);

            return response()->json(['message' => 'Cerveza añadida a favoritos']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la cerveza solicitada.'], 404);
        }
    }

    /**
     * Quitar de favoritos
     *
     * Elimina una cerveza de la lista de favoritos del usuario autenticado.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la cerveza. Example: 1
     *
     * @response {
     *  "message": "Cerveza eliminada de favoritos"
     * }
     */
    public function unfavorite(Request $request, $id): JsonResponse
    {
        try {
            Beer::findOrFail($id);

            Favorite::where('user_id', $request->user()->id)
                ->where('beer_id', $id)
                ->delete();

            return response()->json(['message' => 'Cerveza eliminada de favoritos']);
        } catch (\Exception $e) {
            // Aun si la cerveza no existe, devolvemos éxito ya que la cerveza
            // ya no está en favoritos (lo cual es lo esperado)
            return response()->json(['message' => 'Cerveza eliminada de favoritos']);
        }
    }

    /**
     * Mis cervezas favoritas
     *
     * Obtiene la lista de cervezas favoritas del usuario autenticado.
     *
     * @authenticated
     *
     * @queryParam per_page integer Número de elementos por página (5-50). Example: 10
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": "Mahou Clásica",
     *      "brewery": {
     *        "id": 1,
     *        "name": "Cervecería Mahou"
     *      },
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
     *      "is_favorite": true
     *    }
     *  ],
     *  "links": {...},
     *  "meta": {...}
     * }
     */
    public function favorites(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'per_page' => 'nullable|integer|min:5|max:50',
        ]);

        $perPage = $validated['per_page'] ?? 10;

        $favorites = Beer::whereHas('favorites', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })
            ->with(['brewery', 'style'])
            ->withAvg('checkIns', 'rating')
            ->withCount('checkIns')
            ->paginate($perPage);

        // Marcar todas como favoritas
        $favorites->each(function ($beer) {
            $beer->is_favorite = true;
        });

        return BeerResource::collection($favorites);
    }

    /**
     * Cervezas similares
     *
     * Obtiene una lista de cervezas similares a la cerveza especificada basada en estilo, IBU y ABV.
     *
     * @urlParam id integer required ID de la cerveza. Example: 1
     * @queryParam limit integer Número máximo de cervezas a retornar (1-20). Example: 5
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 3,
     *      "name": "Estrella Damm",
     *      "brewery": {
     *        "id": 3,
     *        "name": "Damm"
     *      },
     *      "style": {
     *        "id": 2,
     *        "name": "Lager"
     *      },
     *      "abv": 4.6,
     *      "ibu": 22,
     *      "description": "Cerveza mediterránea",
     *      "image_url": "https://example.com/beers/estrella_damm.png",
     *      "rating_avg": 3.6,
     *      "check_ins_count": 38,
     *      "is_favorite": false,
     *      "similarity_score": 0.92
     *    }
     *  ]
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado la cerveza solicitada."
     * }
     */
    public function getSimilar(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'limit' => 'nullable|integer|min:1|max:20',
            ]);

            $limit = $validated['limit'] ?? 5;
            $beer = Beer::with(['brewery', 'style'])->findOrFail($id);

            // Calculamos similitud basada en estilo, ABV e IBU
            $similarBeers = Beer::where('id', '!=', $beer->id)
                ->with(['brewery', 'style'])
                ->withAvg('checkIns', 'rating')
                ->withCount('checkIns')
                ->when($beer->style_id, function (Builder $query) use ($beer) {
                    // Mayor peso si es del mismo estilo
                    return $query->where('style_id', $beer->style_id);
                })
                ->when($beer->abv, function (Builder $query) use ($beer) {
                    // ABV similar (±1.5%)
                    $minAbv = max(0, $beer->abv - 1.5);
                    $maxAbv = min(100, $beer->abv + 1.5);
                    return $query->whereBetween('abv', [$minAbv, $maxAbv]);
                })
                ->when($beer->ibu, function (Builder $query) use ($beer) {
                    // IBU similar (±10 unidades)
                    $minIbu = max(0, $beer->ibu - 10);
                    $maxIbu = min(200, $beer->ibu + 10);
                    return $query->whereBetween('ibu', [$minIbu, $maxIbu]);
                })
                ->limit($limit)
                ->get();

            // Calcular puntuación de similitud
            $similarBeers = $similarBeers->map(function ($similarBeer) use ($beer) {
                $styleMatch = $similarBeer->style_id === $beer->style_id ? 1.0 : 0.0;

                $abvScore = 1.0;
                if ($beer->abv && $similarBeer->abv) {
                    $abvDiff = abs($beer->abv - $similarBeer->abv);
                    $abvScore = max(0, 1 - ($abvDiff / 5)); // 5% diferencia = 0 puntos
                }

                $ibuScore = 1.0;
                if ($beer->ibu && $similarBeer->ibu) {
                    $ibuDiff = abs($beer->ibu - $similarBeer->ibu);
                    $ibuScore = max(0, 1 - ($ibuDiff / 50)); // 50 puntos diferencia = 0 puntos
                }

                // Ponderación: estilo 60%, ABV 20%, IBU 20%
                $similarityScore = ($styleMatch * 0.6) + ($abvScore * 0.2) + ($ibuScore * 0.2);
                $similarBeer->similarity_score = round($similarityScore, 2);

                return $similarBeer;
            })->sortByDesc('similarity_score')->values();

            // Si está autenticado, agregar info de favoritos
            if ($request->user()) {
                $userId = $request->user()->id;
                $userFavs = Favorite::where('user_id', $userId)
                    ->whereIn('beer_id', $similarBeers->pluck('id'))
                    ->pluck('beer_id')
                    ->toArray();

                $similarBeers->each(function ($beer) use ($userFavs) {
                    $beer->is_favorite = in_array($beer->id, $userFavs);
                });
            }

            $beerResources = $similarBeers->map(function ($beer) {
                $resource = new BeerResource($beer);
                $data = $resource->toArray(request());
                $data['similarity_score'] = $beer->similarity_score;
                return $data;
            });

            return response()->json(['data' => $beerResources]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la cerveza solicitada.'], 404);
        }
    }

    /**
     * Obtener listado simple de cervezas
     *
     * Obtiene una lista ligera de todas las cervezas con solo ID y nombre.
     * Útil para selectores y autocomplete.
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": "Mahou Clásica"
     *    },
     *    {
     *      "id": 2,
     *      "name": "Estrella Galicia"
     *    }
     *  ]
     * }
     */
    public function getBeers(): JsonResponse
    {
        try {
            $beers = Beer::all()->select('id', 'name');
            return response()->json(['data' => $beers]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las cervezas'
            ], 500);
        }
    }
}
