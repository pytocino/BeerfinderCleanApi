<?php

namespace App\Http\Controllers\API\Beer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BeerReview;
use App\Http\Resources\BeerReviewResource;
use Illuminate\Support\Facades\Validator;

class BeerReviewController extends Controller
{
    // Crear una review
    public function createBeerReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'beer_id' => 'required|exists:beers,id',
            'location_id' => 'nullable|exists:locations,id',
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
        $data['user_id'] = $request->user()->id;

        $review = BeerReview::create($data);

        return new BeerReviewResource($review);
    }

    // Obtener una review por ID
    public function getBeerReviewById($id)
    {
        $review = BeerReview::with(['beer', 'location', 'user', 'post'])->findOrFail($id);
        return new BeerReviewResource($review);
    }
}
