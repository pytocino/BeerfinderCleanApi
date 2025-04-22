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
     *
     * @param int $id ID del usuario a mostrar
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        return response()->json(new UserResource($user));
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json(new UserResource($user));
    }
}
