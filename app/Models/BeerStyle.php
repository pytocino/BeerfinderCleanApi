<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BeerStyle extends Model
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
        'origin_country',
    ];

    /**
     * Obtiene todas las cervezas que pertenecen a este estilo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function beers(): HasMany
    {
        return $this->hasMany(Beer::class, 'style_id');
    }

    /**
     * Obtiene la cantidad de cervezas asociadas a este estilo.
     *
     * @return int
     */
    public function getBeersCount(): int
    {
        return $this->beers()->count();
    }

    /**
     * Obtiene las cervezas mejor valoradas de este estilo.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopRatedBeers(int $limit = 5)
    {
        return $this->beers()
            ->whereNotNull('avg_rating')
            ->orderByDesc('avg_rating')
            ->limit($limit)
            ->get();
    }

    /**
     * Calcula y devuelve la valoración media de todas las cervezas de este estilo.
     *
     * @return float|null
     */
    public function calculateAverageRating(): ?float
    {
        return $this->beers()
            ->whereNotNull('avg_rating')
            ->avg('avg_rating');
    }

    /**
     * Obtiene las cervecerías que producen cervezas de este estilo.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getBreweries()
    {
        return Brewery::whereHas('beers', function ($query) {
            $query->where('style_id', $this->id);
        })->get();
    }

    /**
     * Scope para filtrar estilos por país de origen.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $country
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromCountry($query, string $country)
    {
        return $query->where('origin_country', $country);
    }

    /**
     * Scope para filtrar estilos por popularidad (cantidad de cervezas).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $minBeers
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopular($query, int $minBeers = 5)
    {
        return $query->withCount('beers')
            ->having('beers_count', '>=', $minBeers)
            ->orderByDesc('beers_count');
    }

    /**
     * Obtiene estilos relacionados basados en cervezas comunes.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedStyles(int $limit = 3)
    {
        $styleIds = Beer::where('style_id', $this->id)
            ->join('beers as b2', 'beers.brewery_id', '=', 'b2.brewery_id')
            ->where('b2.style_id', '!=', $this->id)
            ->pluck('b2.style_id')
            ->unique();

        return BeerStyle::whereIn('id', $styleIds)
            ->limit($limit)
            ->get();
    }

    /**
     * Retorna una descripción resumida del estilo.
     *
     * @param int $length
     * @return string
     */
    public function getShortDescription(int $length = 100): string
    {
        if (!$this->description) {
            return '';
        }

        return strlen($this->description) > $length
            ? substr($this->description, 0, $length) . '...'
            : $this->description;
    }
}
