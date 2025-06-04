<?php

namespace App\Http\Controllers\API\Location;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeerResource;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Models\Beer;
use App\Traits\HasUser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class NearbyController extends Controller
{
    use HasUser;

    /**
     * Encuentra cervezas cercanas basándose en la ubicación del usuario
     */
    public function getNearbyBeers(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0.1|max:100', // Radio en kilómetros
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $userLat = $validated['latitude'];
        $userLng = $validated['longitude'];
        $radius = $validated['radius'] ?? 10; // 10km por defecto
        $limit = $validated['limit'] ?? 20;

        // Usar la fórmula Haversine para calcular distancias
        $beers = Beer::select('beers.*')
            ->selectRaw('
                locations.name as location_name,
                locations.address as location_address,
                locations.latitude as location_latitude,
                locations.longitude as location_longitude,
                beer_locations.price,
                beer_locations.is_featured,
                (
                    6371 * acos(
                        cos(radians(?)) 
                        * cos(radians(locations.latitude)) 
                        * cos(radians(locations.longitude) - radians(?)) 
                        + sin(radians(?)) 
                        * sin(radians(locations.latitude))
                    )
                ) AS distance_km
            ', [$userLat, $userLng, $userLat])
            ->join('beer_locations', 'beers.id', '=', 'beer_locations.beer_id')
            ->join('locations', 'beer_locations.location_id', '=', 'locations.id')
            ->whereNotNull('locations.latitude')
            ->whereNotNull('locations.longitude')
            ->where('locations.status', 'active')
            ->havingRaw('distance_km <= ?', [$radius])
            ->orderBy('distance_km')
            ->with(['style', 'brewery'])
            ->limit($limit)
            ->get();

        // Añadir información de distancia y ubicación a cada cerveza
        $beersWithLocation = $beers->map(function ($beer) {
            $beer->distance_km = round($beer->distance_km, 2);
            $beer->location_info = [
                'name' => $beer->location_name,
                'address' => $beer->location_address,
                'latitude' => $beer->location_latitude,
                'longitude' => $beer->location_longitude,
                'price' => $beer->price,
                'is_featured' => $beer->is_featured,
            ];
            
            // Limpiar atributos temporales
            unset($beer->location_name, $beer->location_address, 
                  $beer->location_latitude, $beer->location_longitude, 
                  $beer->price, $beer->is_featured);
            
            return $beer;
        });

        return response()->json([
            'beers' => BeerResource::collection($beersWithLocation),
            'total' => $beersWithLocation->count(),
            'search_radius_km' => $radius,
            'user_location' => [
                'latitude' => $userLat,
                'longitude' => $userLng
            ]
        ]);
    }

    /**
     * Encuentra ubicaciones (bares, restaurantes, etc.) cercanas
     */
    public function getNearbyLocations(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0.1|max:50',
            'type' => 'nullable|string|in:bar,restaurant,brewery,store,all',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $userLat = $validated['latitude'];
        $userLng = $validated['longitude'];
        $radius = $validated['radius'] ?? 5; // 5km por defecto para ubicaciones
        $type = $validated['type'] ?? 'all';
        $limit = $validated['limit'] ?? 20;

        $query = Location::selectRaw('
            locations.*,
            (
                6371 * acos(
                cos(radians(?)) 
                * cos(radians(latitude)) 
                * cos(radians(longitude) - radians(?)) 
                + sin(radians(?)) 
                * sin(radians(latitude))
                )
            ) AS distance_km
            ', [$userLat, $userLng, $userLat])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('status', 'active')
            ->havingRaw('distance_km <= ?', [$radius])
            ->orderBy('distance_km');

        // Filtrar por tipo si se especifica
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $locations = $query->with(['beers' => function($query) {
                $query->limit(5); // Mostrar solo 5 cervezas por ubicación
            }])
            ->limit($limit)
            ->get();

        // Añadir distancia redondeada
        $locations->each(function ($location) {
            $location->distance_km = round($location->distance_km, 2);
        });

        return response()->json([
            'locations' => LocationResource::collection($locations),
            'total' => $locations->count(),
            'search_radius_km' => $radius,
            'filter_type' => $type,
            'user_location' => [
                'latitude' => $userLat,
                'longitude' => $userLng
            ]
        ]);
    }

    /**
     * Obtiene estadísticas de proximidad para el usuario
     */
    public function getNearbyStats(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $userLat = $validated['latitude'];
        $userLng = $validated['longitude'];

        // Contar ubicaciones por distancia
        $stats = [
            'within_1km' => $this->countLocationsWithinRadius($userLat, $userLng, 1),
            'within_5km' => $this->countLocationsWithinRadius($userLat, $userLng, 5),
            'within_10km' => $this->countLocationsWithinRadius($userLat, $userLng, 10),
            'within_25km' => $this->countLocationsWithinRadius($userLat, $userLng, 25),
        ];

        // Contar cervezas únicas disponibles cerca
        $nearbyBeersCount = Beer::join('beer_locations', 'beers.id', '=', 'beer_locations.beer_id')
            ->join('locations', 'beer_locations.location_id', '=', 'locations.id')
            ->selectRaw('
                (
                    6371 * acos(
                        cos(radians(?)) 
                        * cos(radians(locations.latitude)) 
                        * cos(radians(locations.longitude) - radians(?)) 
                        + sin(radians(?)) 
                        * sin(radians(locations.latitude))
                    )
                ) AS distance_km
            ', [$userLat, $userLng, $userLat])
            ->whereNotNull('locations.latitude')
            ->whereNotNull('locations.longitude')
            ->havingRaw('distance_km <= ?', [10])
            ->distinct('beers.id')
            ->count();

        return response()->json([
            'location_stats' => $stats,
            'unique_beers_within_10km' => $nearbyBeersCount,
            'user_location' => [
                'latitude' => $userLat,
                'longitude' => $userLng
            ]
        ]);
    }

    /**
     * Cuenta ubicaciones dentro de un radio específico
     */
    private function countLocationsWithinRadius(float $lat, float $lng, float $radius): int
    {
        return Location::selectRaw('
                (
                    6371 * acos(
                        cos(radians(?)) 
                        * cos(radians(latitude)) 
                        * cos(radians(longitude) - radians(?)) 
                        + sin(radians(?)) 
                        * sin(radians(latitude))
                    )
                ) AS distance_km
            ', [$lat, $lng, $lat])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->havingRaw('distance_km <= ?', [$radius])
            ->count();
    }
}
