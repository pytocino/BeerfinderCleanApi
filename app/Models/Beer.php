<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Beer extends Model
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
        'brewery_id',
        'style_id',
        'abv',
        'ibu',
        'image_url',
        'is_verified',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'abv' => 'float',
        'ibu' => 'integer',
        'is_verified' => 'boolean',
    ];

    /**
     * Obtiene la cervecería a la que pertenece esta cerveza.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brewery(): BelongsTo
    {
        return $this->belongsTo(Brewery::class);
    }

    /**
     * Obtiene el estilo de esta cerveza.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function style(): BelongsTo
    {
        return $this->belongsTo(BeerStyle::class, 'style_id');
    }

    /**
     * Obtiene las reseñas de esta cerveza.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(BeerReview::class);
    }

    /**
     * Obtiene los usuarios que han marcado esta cerveza como favorita.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoritedBy(): BelongsToMany
    {
        // Cambiado a la tabla pivote correcta: favorite_beers
        return $this->belongsToMany(User::class, 'favorite_beers', 'favorable_id', 'user_id')
            ->wherePivot('favorable_type', '=', self::class)
            ->withTimestamps();
    }

    /**
     * Obtiene las ubicaciones donde se puede encontrar esta cerveza.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'beer_locations')
            ->withPivot('price', 'is_featured')
            ->withTimestamps();
    }

    /**
     * Obtiene los posts relacionados con esta cerveza.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Devuelve la intensidad del amargor en formato texto.
     *
     * @return string
     */
    public function getBitternessLevel(): string
    {
        if (!$this->ibu) {
            return 'No disponible';
        }

        if ($this->ibu < 20) {
            return 'Suave';
        } elseif ($this->ibu < 40) {
            return 'Moderado';
        } elseif ($this->ibu < 60) {
            return 'Pronunciado';
        } else {
            return 'Muy amargo';
        }
    }

    /**
     * Devuelve la intensidad del alcohol en formato texto.
     *
     * @return string
     */
    public function getAlcoholLevel(): string
    {
        if (!$this->abv) {
            return 'No disponible';
        }

        if ($this->abv < 4.0) {
            return 'Bajo';
        } elseif ($this->abv < 6.0) {
            return 'Medio';
        } elseif ($this->abv < 8.0) {
            return 'Alto';
        } else {
            return 'Muy alto';
        }
    }

    /**
     * Verifica si un usuario ha reseñado esta cerveza.
     *
     * @param int $userId
     * @return bool
     */
    public function isReviewedBy(int $userId): bool
    {
        return $this->reviews()->where('user_id', $userId)->exists();
    }

    /**
     * Verifica si un usuario ha marcado esta cerveza como favorita.
     *
     * @param int $userId
     * @return bool
     */
    public function isFavoritedBy(int $userId): bool
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    /**
     * Scope para filtrar cervezas por estilo.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $styleId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfStyle($query, int $styleId)
    {
        return $query->where('style_id', $styleId);
    }

    /**
     * Scope para filtrar cervezas por cervecería.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $breweryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByBrewery($query, int $breweryId)
    {
        return $query->where('brewery_id', $breweryId);
    }

    /**
     * Scope para filtrar cervezas por contenido de alcohol.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $min
     * @param float|null $max
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAlcoholContent($query, float $min, ?float $max = null)
    {
        $query = $query->where('abv', '>=', $min);

        if ($max !== null) {
            $query = $query->where('abv', '<=', $max);
        }

        return $query;
    }

    /**
     * Scope para filtrar cervezas por nivel de amargor.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $min
     * @param int|null $max
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithBitterness($query, int $min, ?int $max = null)
    {
        $query = $query->where('ibu', '>=', $min);

        if ($max !== null) {
            $query = $query->where('ibu', '<=', $max);
        }

        return $query;
    }

    /**
     * Obtiene cervezas similares basadas en el mismo estilo.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSimilarBeers(int $limit = 5)
    {
        return self::where('id', '!=', $this->id)
            ->where('style_id', $this->style_id)
            ->limit($limit)
            ->get();
    }

    /**
     * Obtiene los favoritos relacionados con esta cerveza.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favorable');
    }
}
