<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brewery;
use App\Http\Resources\BreweryResource;
use Illuminate\Http\JsonResponse;

class BreweryController extends Controller
{
    /**
     * Listar todas las cervecerías.
     */
    public function index(Request $request): JsonResponse
    {
        $breweries = Brewery::withCount('beers')->get();
        return response()->json(BreweryResource::collection($breweries));
    }

    /**
     * Mostrar una cervecería específica.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $brewery = Brewery::with('beers')->withCount('beers')->find($id);

        if (!$brewery) {
            return response()->json(['message' => 'Cervecería no encontrada.'], 404);
        }

        return response()->json(new BreweryResource($brewery));
    }
}
