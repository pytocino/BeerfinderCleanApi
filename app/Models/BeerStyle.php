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
     * Relación: cervecerías que producen cervezas de este estilo.
     */
    public function breweries()
    {
        return $this->hasManyThrough(
            Brewery::class,
            Beer::class,
            'style_id', // Foreign key en Beer
            'id',      // Foreign key en Brewery
            'id',      // Local key en BeerStyle
            'brewery_id' // Local key en Beer
        )->distinct();
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

    /**
     * Devuelve las cervezas mejor valoradas de este estilo.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopRatedBeers($limit = 3)
    {
        return $this->beers()
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->limit($limit)
            ->get();
    }

    /**
     * Calcula los promedios de características típicas de las cervezas de este estilo.
     * Devuelve un array con los valores promedio de cuerpo, amargor y alcohol.
     *
     * @return array
     */
    public function getTypicalCharacteristics(): array
    {
        $beers = $this->beers;
        $count = $beers->count();
        if ($count === 0) {
            return [
                'body' => null,
                'bitterness' => null,
                'alcohol' => null,
            ];
        }
        // Si existe un campo "body" en la tabla beers, usarlo. Si no, dejar null.
        $body = $beers->first()->body ?? null;
        if ($body !== null) {
            // TODO: AÑADIR LA COLUMNA BODY A LA TABLA BEERS
            $body = round($beers->avg('body'), 2);
        }
        $bitterness = round($beers->avg('ibu'), 2); // IBU = International Bitterness Units
        $alcohol = round($beers->avg('abv'), 2);    // ABV = Alcohol by Volume
        return [
            'body' => $body,
            'bitterness' => $bitterness,
            'alcohol' => $alcohol,
        ];
    }
}
