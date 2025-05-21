<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeerResource;
use App\Models\Beer;
use Illuminate\Http\Request;

class BeerController extends Controller
{
    public function index()
    {
        return BeerResource::collection(Beer::with('style')->paginate(20));
    }

    public function show($id)
    {
        $beer = Beer::with('style')->findOrFail($id);
        return new BeerResource($beer);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brewery' => 'required|string|max:255',
            'style_id' => 'required|exists:beer_styles,id',
            'abv' => 'nullable|numeric',
            'ibu' => 'nullable|integer',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
        ]);
        $beer = Beer::create($data);
        return new BeerResource($beer->load('style'));
    }

    public function update(Request $request, $id)
    {
        $beer = Beer::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'brewery' => 'sometimes|required|string|max:255',
            'style_id' => 'sometimes|required|exists:beer_styles,id',
            'abv' => 'nullable|numeric',
            'ibu' => 'nullable|integer',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
        ]);
        $beer->update($data);
        return new BeerResource($beer->fresh('style'));
    }

    public function destroy($id)
    {
        $beer = Beer::findOrFail($id);
        $beer->delete();
        return response()->json(['message' => 'Cerveza eliminada correctamente.']);
    }
}
