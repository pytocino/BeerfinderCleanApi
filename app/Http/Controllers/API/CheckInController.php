<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CheckInResource;
use App\Http\Resources\CommentResource;
use App\Models\Beer;
use App\Models\CheckIn;
use App\Models\User;
use App\Models\Location;
use App\Models\Follow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Builder;

/**
 * @group Check-ins
 *
 * APIs para gestionar check-ins de cervezas de los usuarios
 */
class CheckInController extends Controller
{
    /**
     * Listar check-ins
     *
     * Obtiene un listado paginado de check-ins con opciones de filtrado y ordenamiento.
     *
     * @authenticated
     *
     * @queryParam beer_id integer Filtrar por cerveza. Example: 5
     * @queryParam brewery_id integer Filtrar por cervecería. Example: 3
     * @queryParam style_id integer Filtrar por estilo de cerveza. Example: 8
     * @queryParam location_id integer Filtrar por ubicación. Example: 2
     * @queryParam min_rating integer Calificación mínima (1-5). Example: 4
     * @queryParam user_id integer Filtrar por usuario. Example: 7
     * @queryParam sort string Ordenar por: recent, rating, likes. Example: recent
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
     *        },
     *        "style": {
     *          "id": 8,
     *          "name": "Imperial Stout"
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
     *      "created_at": "2023-04-18T18:30:00.000000Z",
     *      "updated_at": "2023-04-18T18:30:00.000000Z"
     *    }
     *  ],
     *  "links": {...},
     *  "meta": {...}
     * }
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'beer_id' => 'nullable|integer|exists:beers,id',
            'brewery_id' => 'nullable|integer|exists:breweries,id',
            'style_id' => 'nullable|integer|exists:beer_styles,id',
            'location_id' => 'nullable|integer|exists:locations,id',
            'min_rating' => 'nullable|integer|min:1|max:5',
            'user_id' => 'nullable|integer|exists:users,id',
            'sort' => 'nullable|string|in:recent,rating,likes',
            'per_page' => 'nullable|integer|min:5|max:50',
        ]);

        $query = CheckIn::with([
            'user:id,name,profile_picture',
            'beer.brewery',
            'beer.style',
            'location:id,name'
        ])
            ->withCount(['likes', 'comments']);

        // Aplicar filtros
        if (!empty($validated['beer_id'])) {
            $query->where('beer_id', $validated['beer_id']);
        }

        if (!empty($validated['brewery_id'])) {
            $query->whereHas('beer', function (Builder $q) use ($validated) {
                $q->where('brewery_id', $validated['brewery_id']);
            });
        }

        if (!empty($validated['style_id'])) {
            $query->whereHas('beer', function (Builder $q) use ($validated) {
                $q->where('style_id', $validated['style_id']);
            });
        }

        if (!empty($validated['location_id'])) {
            $query->where('location_id', $validated['location_id']);
        }

        if (!empty($validated['min_rating'])) {
            $query->where('rating', '>=', $validated['min_rating']);
        }

        if (!empty($validated['user_id'])) {
            $query->where('user_id', $validated['user_id']);
        }

        // Ordenar resultados
        $sort = $validated['sort'] ?? 'recent';

        switch ($sort) {
            case 'rating':
                $query->orderByDesc('rating')
                    ->orderByDesc('created_at');
                break;
            case 'likes':
                $query->orderByDesc('likes_count')
                    ->orderByDesc('created_at');
                break;
            default: // 'recent'
                $query->orderByDesc('created_at');
                break;
        }

        // Si el usuario está autenticado, agregar información de si le dio like
        if ($request->user()) {
            $userId = $request->user()->id;
            $query->addSelect([
                'is_liked' => \App\Models\Like::selectRaw('COUNT(*)')
                    ->whereColumn('check_in_id', 'check_ins.id')
                    ->where('user_id', $userId)
            ]);
        }

        $perPage = $validated['per_page'] ?? 10;

        return CheckInResource::collection($query->paginate($perPage));
    }

    /**
     * Ver check-in
     *
     * Muestra información detallada de un check-in específico, incluyendo comentarios.
     *
     * @urlParam id integer required ID del check-in. Example: 42
     *
     * @response {
     *  "data": {
     *    "id": 42,
     *    "user": {
     *      "id": 3,
     *      "name": "Carlos Ruiz",
     *      "profile_picture": "https://example.com/avatars/carlos.jpg"
     *    },
     *    "beer": {
     *      "id": 5,
     *      "name": "Founders Breakfast Stout",
     *      "brewery": {
     *        "id": 3,
     *        "name": "Founders Brewing Co."
     *      },
     *      "style": {
     *        "id": 8,
     *        "name": "Imperial Stout"
     *      }
     *    },
     *    "rating": 4.5,
     *    "comment": "Excelente balance entre café y chocolate",
     *    "photo_url": "https://example.com/photos/check_in_42.jpg",
     *    "location": {
     *      "id": 2,
     *      "name": "Beer Garden Madrid"
     *    },
     *    "likes_count": 15,
     *    "comments": [
     *      {
     *        "id": 112,
     *        "user": {
     *          "id": 7,
     *          "name": "María López",
     *          "profile_picture": "https://example.com/avatars/maria.jpg"
     *        },
     *        "content": "Totalmente de acuerdo, una maravilla de cerveza.",
     *        "created_at": "2023-04-18T19:15:00.000000Z"
     *      }
     *    ],
     *    "is_liked": false,
     *    "created_at": "2023-04-18T18:30:00.000000Z",
     *    "updated_at": "2023-04-18T18:30:00.000000Z"
     *  }
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el check-in solicitado."
     * }
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $checkIn = CheckIn::with([
                'user:id,name,profile_picture',
                'beer.brewery',
                'beer.style',
                'location:id,name,address',
                'comments.user:id,name,profile_picture'
            ])
                ->withCount('likes')
                ->findOrFail($id);

            // Si el usuario está autenticado, determinar si le ha dado like
            if ($request->user()) {
                $checkIn->is_liked = \App\Models\Like::where('check_in_id', $id)
                    ->where('user_id', $request->user()->id)
                    ->exists();
            }

            return response()->json(['data' => new CheckInResource($checkIn)]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el check-in solicitado.'], 404);
        }
    }

    /**
     * Crear check-in
     *
     * Registra un nuevo check-in de cerveza.
     *
     * @authenticated
     *
     * @bodyParam beer_id integer required ID de la cerveza. Example: 5
     * @bodyParam rating numeric required Calificación de 1 a 5. Example: 4.5
     * @bodyParam comment string Comentario sobre la cerveza. Example: Excelente balance entre café y chocolate
     * @bodyParam location_id integer ID de la ubicación donde se consumió. Example: 2
     * @bodyParam photo file Foto de la cerveza (JPG, PNG, WebP, máx 2MB).
     * @bodyParam serving string Forma de servir (draft, bottle, can). Example: draft
     * @bodyParam purchase_price numeric Precio pagado por la cerveza. Example: 6.50
     * @bodyParam purchase_currency string Moneda del precio (EUR, USD, etc). Example: EUR
     * @bodyParam flavor_notes array Array de notas de sabor. Example: ["café", "chocolate", "vainilla"]
     *
     * @response 201 {
     *  "data": {
     *    "id": 42,
     *    "user": {
     *      "id": 3,
     *      "name": "Carlos Ruiz",
     *      "profile_picture": "https://example.com/avatars/carlos.jpg"
     *    },
     *    "beer": {
     *      "id": 5,
     *      "name": "Founders Breakfast Stout",
     *      "brewery": {
     *        "id": 3,
     *        "name": "Founders Brewing Co."
     *      },
     *      "style": {
     *        "id": 8,
     *        "name": "Imperial Stout"
     *      }
     *    },
     *    "rating": 4.5,
     *    "comment": "Excelente balance entre café y chocolate",
     *    "photo_url": "https://example.com/photos/check_in_42.jpg",
     *    "location": {
     *      "id": 2,
     *      "name": "Beer Garden Madrid"
     *    },
     *    "likes_count": 0,
     *    "comments_count": 0,
     *    "serving": "draft",
     *    "purchase_price": 6.50,
     *    "purchase_currency": "EUR",
     *    "flavor_notes": ["café", "chocolate", "vainilla"],
     *    "created_at": "2023-04-18T18:30:00.000000Z",
     *    "updated_at": "2023-04-18T18:30:00.000000Z"
     *  }
     * }
     *
     * @response 422 {
     *  "message": "Los datos proporcionados no son válidos.",
     *  "errors": {
     *    "beer_id": ["El campo beer id es obligatorio."],
     *    "rating": ["El campo rating debe estar entre 1 y 5."]
     *  }
     * }
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'beer_id' => 'required|integer|exists:beers,id',
                'rating' => 'required|numeric|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
                'location_id' => 'nullable|integer|exists:locations,id',
                'photo' => 'nullable|file|image|max:2048|mimes:jpg,png,jpeg,webp',
                'serving' => 'nullable|string|in:draft,bottle,can',
                'purchase_price' => 'nullable|numeric|min:0',
                'purchase_currency' => 'nullable|string|max:3',
                'flavor_notes' => 'nullable|array',
                'flavor_notes.*' => 'string|max:50',
            ]);

            // Añadir el usuario actual
            $validated['user_id'] = $request->user()->id;

            // Si se sube una foto, procesarla
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $path = $request->file('photo')->store('check-ins', 'public');
                $validated['photo_url'] = Storage::url($path);
            }

            // Convertir notas de sabor a JSON si existen
            if (!empty($validated['flavor_notes'])) {
                $validated['flavor_notes'] = json_encode($validated['flavor_notes']);
            }

            $checkIn = CheckIn::create($validated);

            // Cargar relaciones para la respuesta
            $checkIn->load([
                'user:id,name,profile_picture',
                'beer.brewery',
                'beer.style',
                'location:id,name'
            ]);

            // Notificar a los seguidores
            $this->notifyFollowers($checkIn);

            // Actualizar estadísticas de la cerveza
            $this->updateBeerStats($checkIn->beer_id);

            return response()->json(['data' => new CheckInResource($checkIn)], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear el check-in.'], 500);
        }
    }

    /**
     * Actualizar check-in
     *
     * Actualiza la información de un check-in existente.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del check-in. Example: 42
     * @bodyParam rating numeric Calificación de 1 a 5. Example: 4.0
     * @bodyParam comment string Comentario sobre la cerveza. Example: Un sabor potente pero equilibrado
     * @bodyParam location_id integer ID de la ubicación donde se consumió. Example: 3
     * @bodyParam photo file Foto de la cerveza (JPG, PNG, WebP, máx 2MB).
     * @bodyParam serving string Forma de servir (draft, bottle, can). Example: bottle
     * @bodyParam purchase_price numeric Precio pagado por la cerveza. Example: 5.95
     * @bodyParam purchase_currency string Moneda del precio (EUR, USD, etc). Example: EUR
     * @bodyParam flavor_notes array Array de notas de sabor. Example: ["café", "chocolate", "avellanas"]
     *
     * @response {
     *  "data": {
     *    "id": 42,
     *    "user": {
     *      "id": 3,
     *      "name": "Carlos Ruiz",
     *      "profile_picture": "https://example.com/avatars/carlos.jpg"
     *    },
     *    "beer": {
     *      "id": 5,
     *      "name": "Founders Breakfast Stout",
     *      "brewery": {
     *        "id": 3,
     *        "name": "Founders Brewing Co."
     *      },
     *      "style": {
     *        "id": 8,
     *        "name": "Imperial Stout"
     *      }
     *    },
     *    "rating": 4.0,
     *    "comment": "Un sabor potente pero equilibrado",
     *    "photo_url": "https://example.com/photos/check_in_42.jpg",
     *    "location": {
     *      "id": 3,
     *      "name": "Craft Beer Shop"
     *    },
     *    "likes_count": 15,
     *    "comments_count": 3,
     *    "serving": "bottle",
     *    "purchase_price": 5.95,
     *    "purchase_currency": "EUR",
     *    "flavor_notes": ["café", "chocolate", "avellanas"],
     *    "created_at": "2023-04-18T18:30:00.000000Z",
     *    "updated_at": "2023-04-18T19:45:00.000000Z"
     *  }
     * }
     *
     * @response 403 {
     *  "message": "No tienes permiso para actualizar este check-in."
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el check-in solicitado."
     * }
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $checkIn = CheckIn::findOrFail($id);

            // Verificar permisos (solo el propietario o administrador)
            if ($request->user()->id !== $checkIn->user_id && !$request->user()->is_admin) {
                return response()->json(['message' => 'No tienes permiso para actualizar este check-in.'], 403);
            }

            $validated = $request->validate([
                'rating' => 'sometimes|numeric|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
                'location_id' => 'nullable|integer|exists:locations,id',
                'photo' => 'nullable|file|image|max:2048|mimes:jpg,png,jpeg,webp',
                'serving' => 'nullable|string|in:draft,bottle,can',
                'purchase_price' => 'nullable|numeric|min:0',
                'purchase_currency' => 'nullable|string|max:3',
                'flavor_notes' => 'nullable|array',
                'flavor_notes.*' => 'string|max:50',
            ]);

            // Si se sube una nueva foto, procesarla
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                // Eliminar foto anterior si existe
                if ($checkIn->photo_url && str_starts_with($checkIn->photo_url, '/storage/')) {
                    $oldPath = str_replace('/storage/', 'public/', $checkIn->photo_url);
                    Storage::delete($oldPath);
                }

                $path = $request->file('photo')->store('check-ins', 'public');
                $validated['photo_url'] = Storage::url($path);
            }

            // Convertir notas de sabor a JSON si existen
            if (!empty($validated['flavor_notes'])) {
                $validated['flavor_notes'] = json_encode($validated['flavor_notes']);
            }

            $checkIn->update($validated);

            // Recargar relaciones para la respuesta
            $checkIn->load([
                'user:id,name,profile_picture',
                'beer.brewery',
                'beer.style',
                'location:id,name'
            ]);
            $checkIn->loadCount(['likes', 'comments']);

            // Actualizar estadísticas de la cerveza si cambió el rating
            if (isset($validated['rating'])) {
                $this->updateBeerStats($checkIn->beer_id);
            }

            return response()->json(['data' => new CheckInResource($checkIn)]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el check-in solicitado.'], 404);
        }
    }

    /**
     * Eliminar check-in
     *
     * Elimina un check-in del sistema.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del check-in. Example: 42
     *
     * @response 204 {}
     *
     * @response 403 {
     *  "message": "No tienes permiso para eliminar este check-in."
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el check-in solicitado."
     * }
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $checkIn = CheckIn::findOrFail($id);

            // Verificar permisos (solo el propietario o administrador)
            if ($request->user()->id !== $checkIn->user_id && !$request->user()->is_admin) {
                return response()->json(['message' => 'No tienes permiso para eliminar este check-in.'], 403);
            }

            // Guardar el ID de la cerveza para actualizar estadísticas después
            $beerId = $checkIn->beer_id;

            // Eliminar foto si existe
            if ($checkIn->photo_url && str_starts_with($checkIn->photo_url, '/storage/')) {
                $path = str_replace('/storage/', 'public/', $checkIn->photo_url);
                Storage::delete($path);
            }

            // Eliminar el check-in (los likes y comentarios se eliminarán por cascada)
            $checkIn->delete();

            // Actualizar estadísticas de la cerveza
            $this->updateBeerStats($beerId);

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el check-in solicitado.'], 404);
        }
    }

    /**
     * Check-ins de un usuario
     *
     * Obtiene todos los check-ins realizados por un usuario específico.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del usuario. Example: 3
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     * @queryParam sort string Ordenar por: recent, rating. Example: recent
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 42,
     *      "beer": {
     *        "id": 5,
     *        "name": "Founders Breakfast Stout",
     *        "brewery": {
     *          "id": 3,
     *          "name": "Founders Brewing Co."
     *        },
     *        "style": {
     *          "id": 8,
     *          "name": "Imperial Stout"
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
     *  "user": {
     *    "id": 3,
     *    "name": "Carlos Ruiz",
     *    "profile_picture": "https://example.com/avatars/carlos.jpg",
     *    "check_ins_count": 42
     *  },
     *  "links": {...},
     *  "meta": {...}
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el usuario solicitado."
     * }
     */
    public function getUserCheckIns(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'per_page' => 'nullable|integer|min:5|max:50',
                'sort' => 'nullable|string|in:recent,rating',
            ]);

            $perPage = $validated['per_page'] ?? 10;
            $sort = $validated['sort'] ?? 'recent';

            $query = CheckIn::where('user_id', $id)
                ->with([
                    'beer.brewery',
                    'beer.style',
                    'location:id,name'
                ])
                ->withCount(['likes', 'comments']);

            // Aplicar ordenamiento
            if ($sort === 'rating') {
                $query->orderByDesc('rating')
                    ->orderByDesc('created_at');
            } else {
                $query->orderByDesc('created_at');
            }

            // Si el usuario está autenticado, determinar si le ha dado like
            if ($request->user()) {
                $userId = $request->user()->id;
                $query->addSelect([
                    'is_liked' => \App\Models\Like::selectRaw('COUNT(*)')
                        ->whereColumn('check_in_id', 'check_ins.id')
                        ->where('user_id', $userId)
                ]);
            }

            $checkIns = $query->paginate($perPage);

            // Obtener conteo total de check-ins del usuario
            $totalCheckIns = CheckIn::where('user_id', $id)->count();

            return response()->json([
                'data' => CheckInResource::collection($checkIns),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_picture' => $user->profile_picture,
                    'check_ins_count' => $totalCheckIns
                ],
                'links' => $checkIns->links()->toArray(),
                'meta' => $checkIns->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el usuario solicitado.'], 404);
        }
    }

    /**
     * Check-ins de una cerveza
     *
     * Obtiene todos los check-ins realizados para una cerveza específica.
     *
     * @authenticated
     *
     * @urlParam id integer required ID de la cerveza. Example: 5
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     * @queryParam sort string Ordenar por: recent, rating, likes. Example: likes
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
     *    "style": {
     *      "id": 8,
     *      "name": "Imperial Stout"
     *    },
     *    "check_ins_count": 87,
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
    public function getBeerCheckIns(Request $request, $id): JsonResponse
    {
        try {
            $beer = Beer::with(['brewery', 'style'])->findOrFail($id);

            $validated = $request->validate([
                'per_page' => 'nullable|integer|min:5|max:50',
                'sort' => 'nullable|string|in:recent,rating,likes',
            ]);

            $perPage = $validated['per_page'] ?? 10;
            $sort = $validated['sort'] ?? 'recent';

            $query = CheckIn::where('beer_id', $id)
                ->with([
                    'user:id,name,profile_picture',
                    'location:id,name'
                ])
                ->withCount(['likes', 'comments']);

            // Aplicar ordenamiento
            switch ($sort) {
                case 'rating':
                    $query->orderByDesc('rating')
                        ->orderByDesc('created_at');
                    break;
                case 'likes':
                    $query->orderByDesc('likes_count')
                        ->orderByDesc('created_at');
                    break;
                default: // 'recent'
                    $query->orderByDesc('created_at');
                    break;
            }

            // Si el usuario está autenticado, determinar si le ha dado like
            if ($request->user()) {
                $userId = $request->user()->id;
                $query->addSelect([
                    'is_liked' => \App\Models\Like::selectRaw('COUNT(*)')
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
                    'style' => [
                        'id' => $beer->style->id,
                        'name' => $beer->style->name
                    ],
                    'check_ins_count' => $stats->check_ins_count,
                    'avg_rating' => round($stats->avg_rating ?? 0, 1)
                ],
                'links' => $checkIns->links()->toArray(),
                'meta' => $checkIns->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado la cerveza solicitada.'], 404);
        }
    }

    /**
     * Notifica a los seguidores sobre un nuevo check-in
     */
    private function notifyFollowers(CheckIn $checkIn): void
    {
        // Obtener todos los seguidores del usuario
        $followers = Follow::where('following_id', $checkIn->user_id)
            ->pluck('follower_id')
            ->toArray();

        if (empty($followers)) {
            return;
        }

        // Crear notificaciones para cada seguidor
        foreach ($followers as $followerId) {
            \App\Models\Notification::create([
                'user_id' => $followerId,
                'type' => 'check_in',
                'from_user_id' => $checkIn->user_id,
                'related_id' => $checkIn->id,
                'is_read' => false,
                'data' => [
                    'check_in_id' => $checkIn->id,
                    'beer_name' => $checkIn->beer->name,
                    'rating' => $checkIn->rating
                ],
            ]);
        }
    }

    /**
     * Actualiza las estadísticas de una cerveza
     */
    private function updateBeerStats(int $beerId): void
    {
        $beer = Beer::find($beerId);
        if (!$beer) return;

        // Actualizar rating promedio
        $stats = CheckIn::where('beer_id', $beerId)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as count')
            ->first();

        $beer->rating_avg = $stats->avg_rating ?? 0;
        $beer->check_ins_count = $stats->count ?? 0;
        $beer->save();
    }
}
