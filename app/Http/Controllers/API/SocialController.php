<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Social
 *
 * APIs para gestionar interacciones sociales entre usuarios
 */
class SocialController extends Controller
{
    /**
     * Seguir usuario
     *
     * Comienza a seguir a un usuario.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del usuario a seguir. Example: 2
     *
     * @response {
     *  "message": "Ahora sigues a este usuario."
     * }
     *
     * @response 400 {
     *  "message": "No puedes seguirte a ti mismo."
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el usuario solicitado."
     * }
     *
     * @response 409 {
     *  "message": "Ya sigues a este usuario."
     * }
     */
    public function follow(Request $request, $id): JsonResponse
    {
        try {
            $userToFollow = User::findOrFail($id);
            $currentUser = $request->user();

            // Verificar que no intenta seguirse a sí mismo
            if ($currentUser->id == $userToFollow->id) {
                return response()->json(['message' => 'No puedes seguirte a ti mismo.'], 400);
            }

            // Verificar si ya lo sigue
            $alreadyFollowing = Follow::where('follower_id', $currentUser->id)
                ->where('followed_id', $userToFollow->id)
                ->exists();

            if ($alreadyFollowing) {
                return response()->json(['message' => 'Ya sigues a este usuario.'], 409);
            }

            // Crear la relación de seguimiento
            Follow::create([
                'follower_id' => $currentUser->id,
                'followed_id' => $userToFollow->id
            ]);

            // Crear notificación
            $userToFollow->notifications()->create([
                'type' => 'follow',
                'from_user_id' => $currentUser->id,
                'data' => json_encode(['follower_name' => $currentUser->name])
            ]);

            return response()->json(['message' => 'Ahora sigues a este usuario.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el usuario solicitado.'], 404);
        }
    }

    /**
     * Dejar de seguir usuario
     *
     * Deja de seguir a un usuario.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del usuario que se dejará de seguir. Example: 2
     *
     * @response {
     *  "message": "Has dejado de seguir a este usuario."
     * }
     *
     * @response 404 {
     *  "message": "No estás siguiendo a este usuario."
     * }
     */
    public function unfollow(Request $request, $id): JsonResponse
    {
        try {
            User::findOrFail($id); // Comprobar que el usuario existe
            $currentUser = $request->user();

            // Eliminar la relación de seguimiento
            $deleted = Follow::where('follower_id', $currentUser->id)
                ->where('followed_id', $id)
                ->delete();

            if ($deleted) {
                return response()->json(['message' => 'Has dejado de seguir a este usuario.']);
            } else {
                return response()->json(['message' => 'No estás siguiendo a este usuario.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el usuario solicitado.'], 404);
        }
    }

    /**
     * Seguidores del usuario
     *
     * Obtiene la lista de usuarios que siguen al usuario especificado.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del usuario. Example: 1
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 2,
     *      "name": "María López",
     *      "bio": "Aficionada a las cervezas belgas",
     *      "location": "Valencia, España",
     *      "profile_picture": "https://example.com/avatars/maria.jpg",
     *      "check_ins_count": 28,
     *      "is_following": true,
     *      "created_at": "2023-02-10T00:00:00.000000Z"
     *    }
     *  ],
     *  "links": {...},
     *  "meta": {...}
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el usuario solicitado."
     * }
     */
    public function followers(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'per_page' => 'nullable|integer|min:5|max:50',
            ]);

            $perPage = $validated['per_page'] ?? 10;

            // Obtener seguidores con paginación
            $followers = $user->followers()
                ->withCount('checkIns')
                ->paginate($perPage);

            // Si hay usuario autenticado, comprobar relaciones de seguimiento
            if ($request->user()) {
                $currentUserFollowing = Follow::where('follower_id', $request->user()->id)
                    ->pluck('followed_id')
                    ->toArray();

                foreach ($followers as $follower) {
                    $follower->is_following = in_array($follower->id, $currentUserFollowing);
                }
            }

            return response()->json([
                'data' => UserResource::collection($followers),
                'links' => $followers->links()->toArray(),
                'meta' => $followers->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el usuario solicitado.'], 404);
        }
    }

    /**
     * Usuarios seguidos
     *
     * Obtiene la lista de usuarios que el usuario especificado sigue.
     *
     * @authenticated
     *
     * @urlParam id integer required ID del usuario. Example: 1
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 3,
     *      "name": "Carlos Ruiz",
     *      "bio": "Homebrewer y juez certificado BJCP",
     *      "location": "Barcelona, España",
     *      "profile_picture": "https://example.com/avatars/carlos.jpg",
     *      "check_ins_count": 150,
     *      "is_following": false,
     *      "created_at": "2022-11-05T00:00:00.000000Z"
     *    }
     *  ],
     *  "links": {...},
     *  "meta": {...}
     * }
     *
     * @response 404 {
     *  "message": "No se ha encontrado el usuario solicitado."
     * }
     */
    public function following(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'per_page' => 'nullable|integer|min:5|max:50',
            ]);

            $perPage = $validated['per_page'] ?? 10;

            // Obtener seguidos con paginación
            $following = $user->following()
                ->withCount('checkIns')
                ->paginate($perPage);

            // Si hay usuario autenticado, comprobar relaciones de seguimiento
            if ($request->user()) {
                $currentUserFollowing = Follow::where('follower_id', $request->user()->id)
                    ->pluck('followed_id')
                    ->toArray();

                foreach ($following as $followed) {
                    $followed->is_following = in_array($followed->id, $currentUserFollowing);
                }
            }

            return response()->json([
                'data' => UserResource::collection($following),
                'links' => $following->links()->toArray(),
                'meta' => $following->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el usuario solicitado.'], 404);
        }
    }
}
