<?php

namespace App\Http\Controllers;

use App\Http\Resources\BeerStyleResource;
use App\Models\BeerStyle;
use Illuminate\Http\Request;

class BeerStyleController extends Controller
{
    public function index()
    {
        return BeerStyleResource::collection(BeerStyle::withCount('beers')->paginate(20));
    }

    public function show($id)
    {
        $style = BeerStyle::withCount('beers')->findOrFail($id);
        return new BeerStyleResource($style);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'origin_country' => 'nullable|string|max:255',
        ]);
        $style = BeerStyle::create($data);
        return new BeerStyleResource($style);
    }

    public function update(Request $request, $id)
    {
        $style = BeerStyle::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'origin_country' => 'nullable|string|max:255',
        ]);
        $style->update($data);
        return new BeerStyleResource($style->fresh());
    }

    public function destroy($id)
    {
        $style = BeerStyle::findOrFail($id);
        $style->delete();
        return response()->json(['message' => 'Estilo eliminado correctamente.']);
    }
}
