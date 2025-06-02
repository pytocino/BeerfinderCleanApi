<?php

namespace App\Http\Controllers\API\Beer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BeerReview;
use App\Http\Resources\BeerReviewResource;
use App\Services\AutoCreationService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BeerReviewController extends Controller
{
    protected $autoCreationService;

    public function __construct(AutoCreationService $autoCreationService)
    {
        $this->autoCreationService = $autoCreationService;
    }

    // Crear una review
    public function createBeerReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // IDs existentes (opcional)
            'beer_id' => 'nullable|exists:beers,id',
            'location_id' => 'nullable|exists:locations,id',
            
            // Datos para crear nuevos registros (si no se proporcionan IDs)
            'beer_name' => 'nullable|string|max:255',
            'location_name' => 'nullable|string|max:255',
            'location_latitude' => 'nullable|numeric|between:-90,90',
            'location_longitude' => 'nullable|numeric|between:-180,180',
            'location_address' => 'nullable|string|max:500',
            
            // Campos obligatorios de la reseña
            'rating' => 'required|numeric|min:0|max:5',
            'review_text' => 'nullable|string',
            'serving_type' => 'nullable|string',
            'purchase_price' => 'nullable|numeric',
            'purchase_currency' => 'nullable|string|size:3',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        try {
            DB::beginTransaction();

            // Resolver beer_id
            $beerId = $this->resolveBeer($data);
            if (!$beerId) {
                return response()->json([
                    'errors' => ['beer' => ['Debe proporcionar un beer_id válido o un beer_name para crear una nueva cerveza']]
                ], 422);
            }

            // Resolver location_id (opcional)
            $locationId = $this->resolveLocation($data);

            // Crear la reseña
            $reviewData = [
                'user_id' => $request->user()->id,
                'beer_id' => $beerId,
                'location_id' => $locationId,
                'rating' => $data['rating'],
                'review_text' => $data['review_text'] ?? null,
                'serving_type' => $data['serving_type'] ?? null,
                'purchase_price' => $data['purchase_price'] ?? null,
                'purchase_currency' => $data['purchase_currency'] ?? null,
                'is_public' => $data['is_public'] ?? true,
            ];

            $review = BeerReview::create($reviewData);

            DB::commit();

            return new BeerReviewResource($review->load(['beer.brewery', 'beer.style', 'location', 'user']));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear beer review', [
                'user_id' => $request->user()->id,
                'data' => $data,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'errors' => ['general' => ['Error al crear la reseña. Por favor, inténtelo de nuevo.']]
            ], 500);
        }
    }

    // Obtener una review por ID
    public function getBeerReviewById($id)
    {
        $review = BeerReview::with(['beer', 'location', 'user', 'post'])->findOrFail($id);
        return new BeerReviewResource($review);
    }

    /**
     * Resuelve el beer_id, creando una nueva cerveza si es necesario
     */
    private function resolveBeer(array $data): ?int
    {
        // Si ya se proporcionó un beer_id, usarlo
        if (!empty($data['beer_id'])) {
            return $data['beer_id'];
        }

        // Si se proporcionó beer_name, buscar o crear la cerveza
        if (!empty($data['beer_name'])) {
            $beer = $this->autoCreationService->findOrCreateBeer(
                $data['beer_name'],
                true
            );
            return $beer ? $beer->id : null;
        }

        return null;
    }

    /**
     * Resuelve el location_id, creando una nueva ubicación si es necesario
     */
    private function resolveLocation(array $data): ?int
    {
        // Si ya se proporcionó un location_id, usarlo
        if (!empty($data['location_id'])) {
            return $data['location_id'];
        }

        // Si se proporcionó location_name, buscar o crear la ubicación
        if (!empty($data['location_name'])) {
            $location = $this->autoCreationService->findOrCreateLocation(
                $data['location_name'],
                $data['location_latitude'] ?? null,
                $data['location_longitude'] ?? null,
                true
            );
            return $location ? $location->id : null;
        }

        return null;
    }
}
