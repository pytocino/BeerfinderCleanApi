<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Usuarios
 *
 * APIs para gestionar usuarios
 */
class UserController extends Controller
{
    /**
     * Muestra la información de un usuario específico
     */
    public function show(int $id): JsonResponse
    {
        $user = User::withCount(['followers', 'following', 'posts'])->findOrFail($id);
        return response()->json(new UserResource($user));
    }

    /**
     * Muestra la información del usuario autenticado
     */
    public function me(): JsonResponse
    {
        $user = auth()->user()?->loadCount(['followers', 'following', 'posts']);
        return response()->json(new UserResource($user));
    }
}
