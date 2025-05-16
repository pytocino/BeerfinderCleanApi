<?php

namespace App\Http\Controllers\API\Beer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BeerStyle;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BeerStyleResource;

class BeerStyleController extends Controller
{
    /**
     * Listar todos los estilos de cerveza.
     */
    public function index(Request $request): JsonResponse
    {
        $styles = BeerStyle::all();
        return response()->json(BeerStyleResource::collection($styles));
    }

    /**
     * Mostrar un estilo de cerveza específico.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $style = BeerStyle::with('beers')->find($id);

        if (!$style) {
            return response()->json(['message' => 'Estilo de cerveza no encontrado.'], 404);
        }

        return response()->json(new BeerStyleResource($style));
    }

    /**
     * Obtener estilo de cerveza por ID
     */
    public function getBeerStyleById($id)
    {
        $style = BeerStyle::with(['beers', 'breweries'])->findOrFail($id);
        return response()->json(new BeerStyleResource($style));
    }
}
