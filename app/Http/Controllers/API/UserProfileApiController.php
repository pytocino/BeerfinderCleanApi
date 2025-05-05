<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserProfileResource;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserProfileApiController extends Controller
{
    /**
     * Mostrar el perfil de usuario por ID.
     */
    public function show($id): JsonResponse
    {
        $profile = UserProfile::with('user')->findOrFail($id);
        return response()->json(new UserProfileResource($profile));
    }

    /**
     * Actualizar el perfil de usuario autenticado.
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'website' => 'nullable|string|max:255|url',
            'phone' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'allow_mentions' => 'nullable|boolean',
            'email_notifications' => 'nullable|boolean',
            'timezone' => 'nullable|string|max:64',
        ]);

        $user->profile()->update($validated);

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'userProfile' => new UserProfileResource($user->profile),
        ]);
    }

    /**
     * Eliminar el perfil de usuario autenticado.
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->profile()->delete();

        return response()->json([
            'message' => 'Perfil eliminado correctamente',
        ]);
    }
}
