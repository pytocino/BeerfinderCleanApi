<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brewery;
use Illuminate\Http\JsonResponse;

class BreweryController extends Controller
{
    /**
     * Listar todas las cervecerías.
     */
    public function index(Request $request): JsonResponse
    {
        $breweries = Brewery::all();
        return response()->json($breweries);
    }

    /**
     * Mostrar una cervecería específica.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $brewery = Brewery::with('beers')->find($id);

        if (!$brewery) {
            return response()->json(['message' => 'Cervecería no encontrada.'], 404);
        }

        return response()->json($brewery);
    }
}
