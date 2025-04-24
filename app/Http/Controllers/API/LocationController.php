<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Http\Resources\LocationResource;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    /**
     * Listar todas las ubicaciones.
     */
    public function index(Request $request): JsonResponse
    {
        $locations = Location::all();
        return response()->json(LocationResource::collection($locations));
    }

    /**
     * Mostrar una ubicación específica.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $location = Location::with('checkIns')->find($id);

        if (!$location) {
            return response()->json(['message' => 'Ubicación no encontrada.'], 404);
        }

        return response()->json(new LocationResource($location));
    }
}
