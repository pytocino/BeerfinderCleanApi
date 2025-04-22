<?php

namespace App\Http\Controllers\API;

use App\Models\Favorite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    /**
     * Mostrar todos los favoritos del usuario autenticado.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $favorites = Favorite::with('beer')
            ->where('user_id', $user->id)
            ->get();

        return response()->json($favorites);
    }

    /**
     * Añadir una cerveza a favoritos.
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        $beerId = $request->input('beer_id');

        if (Favorite::isFavorite($user->id, $beerId)) {
            return response()->json(['message' => 'La cerveza ya está en favoritos.'], 409);
        }

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'beer_id' => $beerId,
        ]);

        return response()->json($favorite, 201);
    }

    /**
     * Mostrar un favorito específico del usuario autenticado.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $favorite = Favorite::with('beer')
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$favorite) {
            return response()->json(['message' => 'Favorito no encontrado.'], 404);
        }

        return response()->json($favorite);
    }

    /**
     * Actualizar el favorito (por ejemplo, cambiar la cerveza favorita).
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $favorite = Favorite::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$favorite) {
            return response()->json(['message' => 'Favorito no encontrado.'], 404);
        }

        $beerId = $request->input('beer_id');
        if (Favorite::isFavorite($user->id, $beerId)) {
            return response()->json(['message' => 'La cerveza ya está en favoritos.'], 409);
        }

        $favorite->beer_id = $beerId;
        $favorite->save();

        return response()->json($favorite);
    }

    /**
     * Eliminar un favorito.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $favorite = Favorite::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$favorite) {
            return response()->json(['message' => 'Favorito no encontrado.'], 404);
        }

        $favorite->delete();

        return response()->json(['message' => 'Favorito eliminado correctamente.']);
    }
}
