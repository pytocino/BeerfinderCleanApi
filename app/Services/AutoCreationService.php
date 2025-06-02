<?php

namespace App\Services;

use App\Models\Beer;
use App\Models\Brewery;
use App\Models\Location;
use App\Models\BeerStyle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoCreationService
{
    /**
     * Crea una cerveza automáticamente si no existe
     *
     * @param string $beerName
     * @return Beer
     */
    public function createBeerIfNotExists(string $beerName): Beer
    {
        try {
            DB::beginTransaction();

            // Buscar si ya existe una cerveza con ese nombre exacto
            $existingBeer = Beer::where('name', $beerName)->first();
            if ($existingBeer) {
                DB::rollBack();
                return $existingBeer;
            }

            // Crear la cerveza como no verificada sin cervecería ni estilo
            $beer = Beer::create([
                'name' => $beerName,
                'description' => 'Cerveza creada automáticamente - Pendiente de verificación',
                'brewery_id' => null,
                'style_id' => null,
                'is_verified' => false,
                'abv' => null,
                'ibu' => null,
                'image_url' => null
            ]);

            DB::commit();

            Log::info('Cerveza creada automáticamente', [
                'beer_id' => $beer->id,
                'beer_name' => $beerName
            ]);

            return $beer;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear cerveza automáticamente', [
                'beer_name' => $beerName,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Crea una ubicación automáticamente si no existe
     *
     * @param string $locationName
     * @param float|null $latitude
     * @param float|null $longitude
     * @param string|null $address
     * @return Location
     */
    public function createLocationIfNotExists(
        string $locationName, 
        ?float $latitude = null, 
        ?float $longitude = null,
        ?string $address = null
    ): Location {
        try {
            DB::beginTransaction();

            // Buscar ubicación existente por nombre y coordenadas aproximadas
            $query = Location::where('name', $locationName);
            
            // Si tenemos coordenadas, buscar ubicaciones cercanas (radio de 100 metros)
            if ($latitude && $longitude) {
                $query->whereRaw('
                    (6371 * acos(
                        cos(radians(?)) 
                        * cos(radians(latitude)) 
                        * cos(radians(longitude) - radians(?)) 
                        + sin(radians(?)) 
                        * sin(radians(latitude))
                    )) <= 0.1
                ', [$latitude, $longitude, $latitude]);
            }

            $existingLocation = $query->first();
            if ($existingLocation) {
                DB::rollBack();
                return $existingLocation;
            }

            // Crear nueva ubicación como no verificada
            $location = Location::create([
                'name' => $locationName,
                'type' => 'other',
                'description' => 'Ubicación creada automáticamente - Pendiente de verificación',
                'status' => 'active',
                'address' => $address,
                'city' => 'Ciudad Desconocida',
                'country' => 'País Desconocido',
                'latitude' => $latitude,
                'longitude' => $longitude,
                'verified' => false
            ]);

            DB::commit();

            Log::info('Ubicación creada automáticamente', [
                'location_id' => $location->id,
                'location_name' => $locationName,
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);

            return $location;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear ubicación automáticamente', [
                'location_name' => $locationName,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Crea una cervecería automáticamente si no existe
     *
     * @param string $breweryName
     * @return Brewery
     */
    private function createBreweryIfNotExists(string $breweryName): Brewery
    {
        // Buscar cervecería existente
        $existingBrewery = Brewery::where('name', $breweryName)->first();
        if ($existingBrewery) {
            return $existingBrewery;
        }

        // Crear nueva cervecería
        $brewery = Brewery::create([
            'name' => $breweryName,
            'description' => 'Cervecería creada automáticamente - Pendiente de verificación',
            'country' => 'País Desconocido',
            'city' => 'Ciudad Desconocida'
        ]);

        Log::info('Cervecería creada automáticamente', [
            'brewery_id' => $brewery->id,
            'brewery_name' => $breweryName
        ]);

        return $brewery;
    }

    /**
     * Busca una cerveza por nombre, opcionalmente creándola si no existe
     *
     * @param string $beerName
     * @param bool $createIfNotExists
     * @return Beer|null
     */
    public function findOrCreateBeer(string $beerName, bool $createIfNotExists = true): ?Beer
    {
        // Primero buscar cerveza existente
        $existingBeer = Beer::where('name', 'LIKE', '%' . $beerName . '%')->first();
        
        if ($existingBeer) {
            return $existingBeer;
        }

        // Si no existe y se permite crear, crearla
        if ($createIfNotExists) {
            return $this->createBeerIfNotExists($beerName);
        }

        return null;
    }

    /**
     * Busca una ubicación por nombre y coordenadas, opcionalmente creándola si no existe
     *
     * @param string $locationName
     * @param float|null $latitude
     * @param float|null $longitude
     * @param bool $createIfNotExists
     * @return Location|null
     */
    public function findOrCreateLocation(
        string $locationName, 
        ?float $latitude = null, 
        ?float $longitude = null, 
        bool $createIfNotExists = true
    ): ?Location {
        // Buscar ubicación existente
        $query = Location::where('name', 'LIKE', '%' . $locationName . '%');
        
        // Si tenemos coordenadas, priorizar ubicaciones cercanas
        if ($latitude && $longitude) {
            $query->orderByRaw('
                (6371 * acos(
                    cos(radians(?)) 
                    * cos(radians(latitude)) 
                    * cos(radians(longitude) - radians(?)) 
                    + sin(radians(?)) 
                    * sin(radians(latitude))
                ))
            ', [$latitude, $longitude, $latitude]);
        }

        $existingLocation = $query->first();
        
        if ($existingLocation) {
            return $existingLocation;
        }

        // Si no existe y se permite crear, crearla
        if ($createIfNotExists) {
            return $this->createLocationIfNotExists($locationName, $latitude, $longitude);
        }

        return null;
    }
}
