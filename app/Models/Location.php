<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'status',
        'opening_hours',
        'address',
        'city',
        'country',
        'latitude',
        'longitude',
        'image_url',
        'cover_photo',
        'website',
        'email',
        'phone',
        'verified',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'opening_hours' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'verified' => 'boolean',
    ];

    /**
     * Relación muchos a muchos: cervezas disponibles en esta ubicación.
     */
    public function beers(): BelongsToMany
    {
        return $this->belongsToMany(Beer::class, 'beer_locations', 'location_id', 'beer_id')
            ->withPivot(['price', 'is_featured'])
            ->withTimestamps();
    }

    /**
     * Reviews de cervezas hechas en esta ubicación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function beerReviews(): HasMany
    {
        return $this->hasMany(BeerReview::class);
    }

    /**
     * Posts relacionados con esta ubicación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Verifica si la ubicación está abierta en este momento.
     *
     * @return bool|null Null si no hay información de horarios
     */
    public function isOpenNow(): ?bool
    {
        if (!$this->opening_hours) {
            return null;
        }

        $now = now();
        $dayOfWeek = strtolower($now->format('l'));
        $currentTime = $now->format('H:i');

        if (!isset($this->opening_hours[$dayOfWeek])) {
            return false;
        }

        foreach ($this->opening_hours[$dayOfWeek] as $timeSlot) {
            if ($currentTime >= $timeSlot['open'] && $currentTime <= $timeSlot['close']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtiene los horarios del día actual.
     *
     * @return array|null
     */
    public function getTodayHours(): ?array
    {
        if (!$this->opening_hours) {
            return null;
        }

        $dayOfWeek = strtolower(now()->format('l'));
        return $this->opening_hours[$dayOfWeek] ?? null;
    }

    /**
     * Obtiene la URL de la imagen de portada o una imagen por defecto.
     *
     * @return string
     */
    public function getCoverPhotoUrl(): string
    {
        if ($this->cover_photo) {
            return $this->cover_photo;
        }

        return asset('images/default-cover.png');
    }

    /**
     * Obtiene la dirección completa, incluyendo ciudad y país.
     *
     * @return string
     */
    public function getFullAddress(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->country
        ]);

        return implode(', ', $parts);
    }

    /**
     * Calcula la distancia entre esta ubicación y unas coordenadas dadas.
     *
     * @param float $latitude
     * @param float $longitude
     * @param string $unit 'km' o 'mi'
     * @return float|null
     */
    public function distanceTo(float $latitude, float $longitude, string $unit = 'km'): ?float
    {
        if ($this->latitude === null || $this->longitude === null) {
            return null;
        }

        // Fórmula Haversine para calcular distancia
        $earthRadius = ($unit === 'mi') ? 3959 : 6371; // Radio de la Tierra en millas o kilómetros

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    /**
     * Verifica si la ubicación está activa.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Obtiene las cervezas destacadas de esta ubicación.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedBeers(int $limit = 5)
    {
        return $this->beers()
            ->wherePivot('is_featured', true)
            ->limit($limit)
            ->get();
    }

    /**
     * Scope para filtrar por tipo de ubicación.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope para filtrar por estado.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para filtrar solo ubicaciones activas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope para ubicaciones verificadas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    /**
     * Scope para filtrar por ciudad.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $city
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInCity($query, string $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope para filtrar por país.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $country
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInCountry($query, string $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Scope para buscar ubicaciones por proximidad.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $latitude
     * @param float $longitude
     * @param float $radius
     * @param string $unit 'km' o 'mi'
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNearby($query, float $latitude, float $longitude, float $radius = 10, string $unit = 'km')
    {
        $earthRadius = ($unit === 'mi') ? 3959 : 6371;

        return $query->whereRaw("
            (
                $earthRadius * acos(
                    cos(radians($latitude)) * 
                    cos(radians(latitude)) * 
                    cos(radians(longitude) - radians($longitude)) + 
                    sin(radians($latitude)) * 
                    sin(radians(latitude))
                )
            ) <= $radius
        ");
    }
}
