<?php

namespace App\Http\Controllers\API\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Http\Resources\LocationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

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

    /**
     * Obtener listado simple de ubicaciones
     *
     * Devuelve una lista de todas las ubicaciones con solo ID y nombre.
     *
     * @response {
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": "Bar El Refugio"
     *    },
     *    {
     *      "id": 2,
     *      "name": "Cervecería La Cebada"
     *    }
     *  ]
     */
    public function getLocations()
    {
        try {
            $locations = Location::all()->select('id', 'name');
            return response()->json(['data' => $locations]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener las ubicaciones'], 500);
        }
    }

    /**
     * Obtener ubicación por ID
     */
    public function getLocationById(Request $request, $id)
    {
        $location = Location::with(['beers'])->findOrFail($id);

        if ($request->has(['latitude', 'longitude'])) {
            $distance = $location->distanceTo($request->latitude, $request->longitude);
            $location->distance_km = $distance !== null ? round($distance, 2) : null;
        }

        return new LocationResource($location);
    }
}
