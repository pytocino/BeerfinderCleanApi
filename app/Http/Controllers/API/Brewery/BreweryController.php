<?php

namespace App\Http\Controllers\API\Brewery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brewery;
use App\Http\Resources\BreweryResource;

class BreweryController extends Controller
{
    /**
     * Obtener una cervecería por su ID.
     *
     * @OA\Get(
     *     path="/breweries/{id}",
     *     summary="Obtener cervecería por ID",
     *     tags={"Brewery"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la cervecería",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos de la cervecería",
     *         @OA\JsonContent(ref="#/components/schemas/BreweryResource")
     *     ),
     *     @OA\Response(response=404, description="No encontrada")
     * )
     */
    public function getBreweryById($id)
    {
        $brewery = Brewery::find($id);
        if (!$brewery) {
            return response()->json(['message' => 'Cervecería no encontrada'], 404);
        }
        return new BreweryResource($brewery);
    }
}
