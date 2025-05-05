<?php

namespace App\Http\Controllers\API\Beer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BeerStyle;
use Illuminate\Http\JsonResponse;

class BeerStyleController extends Controller
{
    /**
     * Listar todos los estilos de cerveza.
     */
    public function index(Request $request): JsonResponse
    {
        $styles = BeerStyle::all();
        return response()->json($styles);
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

        return response()->json($style);
    }
}
