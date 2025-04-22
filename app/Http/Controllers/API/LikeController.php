<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use App\Models\Like;
use App\Models\CheckIn;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @group Likes
 *
 * APIs para gestionar likes en los check-ins
 */
class LikeController extends Controller
{
    /**
     * Dar like a un check-in
     *
     * Registra un like del usuario autenticado en un check-in específico.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del check-in. Example: 42
     *
     * @response {
     *  "message": "Like registrado correctamente.",
     *  "data": {
     *    "id": 105,
     *    "user": {
     *      "id": 7,
     *      "name": "María López",
     *      "profile_picture": "https://example.com/avatars/maria.jpg"
     *    },
     *    "check_in_id": 42,
     *    "created_at": "2023-04-19T15:30:00.000000Z"
     *  },
     *  "likes_count": 16
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el check-in solicitado."
     * }
     *
     * @response 409 {
     *  "message": "Ya has dado like a este check-in."
     * }
     */
    public function likeCheckIn(Request $request, $checkInId): JsonResponse
    {
        try {
            // Verificar que el check-in existe
            $checkIn = CheckIn::with('user')->findOrFail($checkInId);

            // Verificar si el usuario ya dio like
            $existingLike = Like::where('user_id', $request->user()->id)
                ->where('check_in_id', $checkInId)
                ->first();

            if ($existingLike) {
                return response()->json([
                    'message' => 'Ya has dado like a este check-in.'
                ], 409);
            }

            // Crear el like
            $like = Like::create([
                'user_id' => $request->user()->id,
                'check_in_id' => $checkInId
            ]);

            // Cargar usuario para la respuesta
            $like->load('user:id,name,profile_picture');

            // Notificar al dueño del check-in si no es el mismo usuario
            if ($request->user()->id !== $checkIn->user_id) {
                Notification::create([
                    'user_id' => $checkIn->user_id,
                    'type' => 'like',
                    'from_user_id' => $request->user()->id,
                    'data' => json_encode([
                        'check_in_id' => $checkInId,
                        'beer_name' => $checkIn->beer->name ?? 'una cerveza'
                    ]),
                    'read' => false
                ]);
            }

            // Calcular likes totales
            $likesCount = Like::where('check_in_id', $checkInId)->count();

            // Asegurarse de que $checkIn es una instancia única, no una colección
            if ($checkIn instanceof \Illuminate\Database\Eloquent\Collection) {
                $checkIn = $checkIn->first();
            }

            // Actualizar la popularidad del check-in
            $this->updateCheckInPopularity($checkIn);

            return response()->json([
                'message' => 'Like registrado correctamente.',
                'data' => new LikeResource($like),
                'likes_count' => $likesCount
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No se ha encontrado el check-in solicitado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud.'], 500);
        }
    }

    /**
     * Quitar like de un check-in
     *
     * Elimina el like del usuario autenticado de un check-in específico.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del check-in. Example: 42
     *
     * @response {
     *  "message": "Like eliminado correctamente.",
     *  "likes_count": 15
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el check-in o no has dado like."
     * }
     */
    public function unlikeCheckIn(Request $request, $checkInId): JsonResponse
    {
        try {
            // Verificar que el check-in existe
            $checkIn = CheckIn::findOrFail($checkInId);

            // Buscar y eliminar el like
            $deleted = Like::where('user_id', $request->user()->id)
                ->where('check_in_id', $checkInId)
                ->delete();

            if (!$deleted) {
                return response()->json([
                    'message' => 'No se ha encontrado el check-in o no has dado like.'
                ], 404);
            }

            // Calcular likes totales
            $likesCount = Like::where('check_in_id', $checkInId)->count();

            // Asegurarse de que $checkIn es una instancia única, no una colección
            if ($checkIn instanceof \Illuminate\Database\Eloquent\Collection) {
                $checkIn = $checkIn->first();
            }

            // Actualizar la popularidad del check-in
            $this->updateCheckInPopularity($checkIn);

            // Eliminar la notificación relacionada
            Notification::where('type', 'like')
                ->where('from_user_id', $request->user()->id)
                ->whereRaw('JSON_EXTRACT(data, "$.check_in_id") = ?', [$checkInId])
                ->delete();

            return response()->json([
                'message' => 'Like eliminado correctamente.',
                'likes_count' => $likesCount
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No se ha encontrado el check-in solicitado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud.'], 500);
        }
    }

    /**
     * Obtener usuarios que dieron like
     *
     * Obtiene la lista de usuarios que dieron like a un check-in específico.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del check-in. Example: 42
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 105,
     *      "user": {
     *        "id": 7,
     *        "name": "María López",
     *        "profile_picture": "https://example.com/avatars/maria.jpg",
     *        "is_following": true
     *      },
     *      "created_at": "2023-04-19T15:30:00.000000Z"
     *    },
     *    {
     *      "id": 106,
     *      "user": {
     *        "id": 12,
     *        "name": "Carlos Gómez",
     *        "profile_picture": "https://example.com/avatars/carlos.jpg",
     *        "is_following": false
     *      },
     *      "created_at": "2023-04-19T15:35:00.000000Z"
     *    }
     *  ],
     *  "likes_count": 15,
     *  "links": {...},
     *  "meta": {...}
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el check-in solicitado."
     * }
     */
    public function getLikes(Request $request, $checkInId): JsonResponse
    {
        try {
            // Verificar que el check-in existe
            $checkIn = CheckIn::findOrFail($checkInId);

            $validated = $request->validate([
                'per_page' => 'nullable|integer|min:5|max:50',
            ]);

            $perPage = $validated['per_page'] ?? 15;

            // Obtener los likes con los datos del usuario
            $likes = Like::where('check_in_id', $checkInId)
                ->with('user:id,name,profile_picture')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            // Si el usuario está autenticado, determinar a quién sigue
            if ($request->user()) {
                $userId = $request->user()->id;
                $followedUserIds = \App\Models\Follow::where('follower_id', $userId)
                    ->pluck('followed_id')
                    ->toArray();

                foreach ($likes as $like) {
                    if ($like->user) {
                        $like->user->is_following = in_array($like->user->id, $followedUserIds);
                    }
                }
            }

            // Calcular likes totales
            $likesCount = Like::where('check_in_id', $checkInId)->count();

            return response()->json([
                'data' => LikeResource::collection($likes),
                'likes_count' => $likesCount,
                'links' => $likes->links()->toArray(),
                'meta' => $likes->toArray(),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No se ha encontrado el check-in solicitado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la solicitud.'], 500);
        }
    }

    /**
     * Obtener check-ins con like
     *
     * Obtiene todos los check-ins a los que el usuario autenticado ha dado like.
     *
     * @authenticated
     *
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
     *      "created_at": "2023-04-18T18:30:00.000000Z",
     *      "liked_at": "2023-04-19T15:30:00.000000Z"
     *    }
     *  ],
     *  "links": {...},
     *  "meta": {...}
     * }
     */
    public function getLikedCheckIns(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'per_page' => 'nullable|integer|min:5|max:50',
        ]);

        $perPage = $validated['per_page'] ?? 15;
        $userId = $request->user()->id;

        // Obtener los check-ins que el usuario ha dado like
        $likedCheckIns = Like::where('user_id', $userId)
            ->with([
                'checkIn.user:id,name,profile_picture',
                'checkIn.beer.brewery',
                'checkIn.beer.style'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Transformar y formatear la respuesta
        $formattedData = $likedCheckIns->map(function ($like) {
            $checkIn = $like->checkIn;
            $result = [
                'id' => $checkIn->id,
                'user' => [
                    'id' => $checkIn->user->id,
                    'name' => $checkIn->user->name,
                    'profile_picture' => $checkIn->user->profile_picture
                ],
                'beer' => [
                    'id' => $checkIn->beer->id,
                    'name' => $checkIn->beer->name,
                    'brewery' => [
                        'id' => $checkIn->beer->brewery->id,
                        'name' => $checkIn->beer->brewery->name
                    ],
                    'style' => [
                        'id' => $checkIn->beer->style->id,
                        'name' => $checkIn->beer->style->name
                    ]
                ],
                'rating' => $checkIn->rating,
                'created_at' => $checkIn->created_at,
                'liked_at' => $like->created_at
            ];

            return $result;
        });

        return response()->json([
            'data' => $formattedData,
            'links' => $likedCheckIns->links()->toArray(),
            'meta' => $likedCheckIns->toArray(),
        ]);
    }

    /**
     * Actualizar puntuación de popularidad de un check-in
     */
    private function updateCheckInPopularity(CheckIn $checkIn): void
    {
        // Calcular puntuación de popularidad basada en likes, comentarios y tiempo
        $likesCount = Like::where('check_in_id', $checkIn->id)->count();
        $commentsCount = \App\Models\Comment::where('check_in_id', $checkIn->id)->count();

        // Calcular tiempo desde la creación (más reciente = puntuación más alta)
        $hoursAgo = $checkIn->created_at->diffInHours(now());
        $timeScore = max(1, 48 - min(48, $hoursAgo)) / 12; // 0.08-4 puntos para las primeras 48 horas

        // Fórmula de popularidad: likes + (comentarios * 2) + factor_tiempo
        $popularityScore = $likesCount + ($commentsCount * 2) + $timeScore;

        // Actualizar puntuación
        $checkIn->popularity_score = $popularityScore;
        $checkIn->save();
    }
}
