<?php

namespace App\Http\Controllers\API\Brewery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brewery;
use App\Http\Resources\BreweryResource;

class BreweryController extends Controller
{

    public function getBreweries()
    {
        $breweries = Brewery::all();
        return BreweryResource::collection($breweries);
    }

    public function getBreweryById($id)
    {
        $brewery = Brewery::with('beers')->find($id);
        return new BreweryResource($brewery);
    }
}
