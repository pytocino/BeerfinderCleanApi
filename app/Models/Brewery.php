<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brewery extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'country',
        'city',
        'website',
        'image_url',
    ];

    /**
     * Obtiene todas las cervezas producidas por esta cervecería.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function beers(): HasMany
    {
        return $this->hasMany(Beer::class);
    }

    /**
     * Obtiene la cantidad de cervezas asociadas a esta cervecería.
     *
     * @return int
     */
    public function getBeersCount(): int
    {
        return $this->beers()->count();
    }

    /**
     * Retorna la ruta completa de la imagen, o una imagen por defecto si no existe.
     *
     * @return string
     */
    public function getImageUrl(): string
    {
        if ($this->image_url) {
            return $this->image_url;
        }

        return asset('images/default-brewery.png');
    }

    /**
     * Obtiene la ubicación completa (ciudad, país).
     *
     * @return string|null
     */
    public function getFullLocation(): ?string
    {
        $location = [];

        if ($this->city) {
            $location[] = $this->city;
        }

        if ($this->country) {
            $location[] = $this->country;
        }

        return !empty($location) ? implode(', ', $location) : null;
    }

    /**
     * Verifica si la cervecería tiene una ubicación definida.
     *
     * @return bool
     */
    public function hasLocation(): bool
    {
        return !empty($this->city) || !empty($this->country);
    }

    /**
     * Verifica si la cervecería tiene información de contacto.
     *
     * @return bool
     */
    public function hasContactInfo(): bool
    {
        return !empty($this->website);
    }

    /**
     * Scope para filtrar cervecerías por país.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $country
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromCountry($query, string $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Scope para filtrar cervecerías por ciudad.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $city
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromCity($query, string $city)
    {
        return $query->where('city', $city);
    }
}
