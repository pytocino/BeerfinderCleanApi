<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BeerResource;
use App\Http\Resources\BreweryResource;
use App\Http\Resources\BeerStyleResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\UserResource;

class SearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [];

        // Agregar resultados de cervezas si están disponibles
        if (isset($this['beers'])) {
            $result['beers'] = [
                'data' => BeerResource::collection($this['beers']['data']),
                'total' => $this['beers']['total']
            ];
        }

        // Agregar resultados de estilos si están disponibles
        if (isset($this['styles'])) {
            $result['styles'] = [
                'data' => BeerStyleResource::collection($this['styles']['data']),
                'total' => $this['styles']['total']
            ];
        }

        // Agregar resultados de ubicaciones si están disponibles
        if (isset($this['locations'])) {
            $result['locations'] = [
                'data' => LocationResource::collection($this['locations']['data']),
                'total' => $this['locations']['total']
            ];
        }

        // Agregar resultados de usuarios si están disponibles
        if (isset($this['users'])) {
            $result['users'] = [
                'data' => UserResource::collection($this['users']['data']),
                'total' => $this['users']['total']
            ];
        }

        return $result;
    }
}
