<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

/**
 * @group Usuarios
 *
 * APIs para gestionar usuarios
 */
class UserController extends Controller
{
    /**
     * Listar usuarios
     *
     * Obtiene un listado paginado de usuarios.
     *
     * @authenticated
     *
     * @queryParam name string Filtrar por nombre. Example: Juan
     * @queryParam sort string Ordenar por: name, created_at. Example: name
     * @queryParam order string Dirección: asc, desc. Example: asc
     * @queryParam per_page int Elementos por página (5-50). Example: 15
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": "Juan Pérez",
     *      "profile_picture": "https://example.com/avatars/juan.jpg",
     *      "bio": "Amante de las cervezas artesanales",
     *      "location": "Madrid, España"
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

        $query = User::query();

        // Cargar contadores si se necesitan
        if ($request->has('with_counts')) {
            $query->withCount(['checkIns', 'followers', 'following']);
        }

        // Aplicar filtro por nombre
        if (!empty($validated['name'])) {
            $query->where('name', 'like', '%' . $validated['name'] . '%');
        }

        // Ordenar resultados
        $sort = $validated['sort'] ?? 'name';
        $order = $validated['order'] ?? 'asc';
        $query->orderBy($sort, $order);

        // Paginar resultados
        $perPage = $validated['per_page'] ?? 10;

        return UserResource::collection($query->paginate($perPage));
    }

    /**
     * Ver usuario
     *
     * Muestra información de un usuario específico.
     *
     * @urlParam id integer required ID del usuario. Example: 1
     *
     * @response {
     *  "data": {
     *    "id": 1,
     *    "name": "Juan Pérez",
     *    "profile_picture": "https://example.com/avatars/juan.jpg",
     *    "bio": "Amante de las IPA y cervecero casero",
     *    "location": "Madrid, España",
     *    "check_ins_count": 42,
     *    "followers_count": 12,
     *    "following_count": 25
     *  }
     * }
     * @response 404 {
     *  "message": "No se ha encontrado el usuario solicitado."
     * }
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            // Cargar usuario con contadores
            $user = User::withCount(['checkIns', 'followers', 'following'])->findOrFail($id);

            // Verificar si el usuario autenticado sigue a este usuario
            if ($request->user()) {
                $user->is_following = \App\Models\Follow::where('follower_id', $request->user()->id)
                    ->where('followed_id', $user->id)
                    ->exists();
            }

            return response()->json(['data' => new UserResource($user)]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'No se ha encontrado el usuario solicitado.'], 404);
        } catch (\Exception $e) {
            Log::error('Error en UserController::show: ' . $e->getMessage());
            return response()->json(['message' => 'Error al obtener el perfil de usuario.'], 500);
        }
    }

    /**
     * Actualizar usuario
     *
     * Actualiza la información de un usuario existente.
     *
     * @authenticated
     * @urlParam id integer required ID del usuario. Example: 1
     * @bodyParam name string Nombre del usuario. Example: Juan García Pérez
     * @bodyParam bio string Biografía del usuario. Example: Cervecero aficionado desde 2015
     * @bodyParam location string Ubicación del usuario. Example: Barcelona, España
     * @bodyParam profile_picture string URL del avatar.
     * @bodyParam avatar file Imagen del avatar (JPG, PNG, WebP, máx 2MB).
     *
     * @response {
     *  "data": {
     *    "id": 1,
     *    "name": "Juan García Pérez",
     *    "profile_picture": "https://example.com/avatars/juan_nuevo.jpg",
     *    "bio": "Cervecero aficionado desde 2015",
     *    "location": "Barcelona, España",
     *    "check_ins_count": 42,
     *    "followers_count": 12,
     *    "following_count": 25
     *  }
     * }
     * @response 403 {
     *  "message": "No tienes permisos para actualizar este usuario."
     * }
     * @response 404 {
     *  "message": "No se ha encontrado el usuario solicitado."
     * }
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Verificar que solo el propio usuario puede actualizar su perfil
            if ($request->user()->id != $user->id) {
                return response()->json([
                    'message' => 'Solo puedes actualizar tu propio perfil.'
                ], 403);
            }

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'bio' => 'nullable|string|max:500',
                'location' => 'nullable|string|max:255',
                'profile_picture' => 'nullable|url|max:255',
                'avatar' => 'nullable|file|image|max:2048|mimes:jpg,png,jpeg,webp',
            ]);

            // Gestionar subida de avatar
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                // Eliminar avatar anterior si existe
                if ($user->profile_picture && str_starts_with($user->profile_picture, '/storage/')) {
                    $oldPath = str_replace('/storage/', 'public/', $user->profile_picture);
                    Storage::delete($oldPath);
                }

                $path = $request->file('avatar')->store('users/avatars', 'public');
                $validated['profile_picture'] = Storage::url($path);
            }

            $user->update($validated);

            // Recargar contadores para la respuesta
            $user->loadCount(['checkIns', 'followers', 'following']);

            return response()->json(['data' => new UserResource($user)]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'No se ha encontrado el usuario solicitado.'], 404);
        } catch (\Exception $e) {
            Log::error('Error en UserController::update: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar el usuario.'], 500);
        }
    }

    /**
     * Eliminar usuario
     *
     * Elimina un usuario del sistema.
     *
     * @authenticated
     * @urlParam id integer required ID del usuario. Example: 1
     *
     * @response 204 {}
     * @response 403 {
     *  "message": "No tienes permisos para eliminar este usuario."
     * }
     * @response 404 {
     *  "message": "No se ha encontrado el usuario solicitado."
     * }
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Verificar que solo el propio usuario puede eliminar su perfil
            if ($request->user()->id != $user->id) {
                return response()->json([
                    'message' => 'Solo puedes eliminar tu propio perfil.'
                ], 403);
            }

            // Eliminar avatar si existe
            if ($user->profile_picture && str_starts_with($user->profile_picture, '/storage/')) {
                $path = str_replace('/storage/', 'public/', $user->profile_picture);
                Storage::delete($path);
            }

            $user->delete();

            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'No se ha encontrado el usuario solicitado.'], 404);
        } catch (\Exception $e) {
            Log::error('Error en UserController::destroy: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar el usuario.'], 500);
        }
    }
}
