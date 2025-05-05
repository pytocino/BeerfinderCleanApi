<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BeerLocation extends Pivot
{
    use HasFactory;

    /**
     * Indica si la tabla tiene una clave primaria auto-incremental.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'beer_locations';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'beer_id',
        'location_id',
        'price',
        'is_featured',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
        'is_featured' => 'boolean',
    ];

    /**
     * Obtiene la cerveza asociada a esta relación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function beer()
    {
        return $this->belongsTo(Beer::class);
    }

    /**
     * Obtiene la ubicación asociada a esta relación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Formatea el precio para visualización.
     *
     * @param string $currency Símbolo de moneda
     * @return string|null
     */
    public function getFormattedPrice(string $currency = '€'): ?string
    {
        if ($this->price === null) {
            return null;
        }

        return $currency . number_format($this->price, 2);
    }

    /**
     * Determina si el precio es considerado "económico" en comparación con otros.
     *
     * @param float $threshold Porcentaje por debajo del promedio para considerarse económico
     * @return bool|null
     */
    public function isBudgetFriendly(float $threshold = 0.85): ?bool
    {
        if ($this->price === null) {
            return null;
        }

        $avgPrice = self::where('beer_id', $this->beer_id)
            ->whereNotNull('price')
            ->avg('price');

        if (!$avgPrice) {
            return null;
        }

        return $this->price <= ($avgPrice * $threshold);
    }

    /**
     * Verifica si es la ubicación más económica para esta cerveza.
     *
     * @return bool|null
     */
    public function isCheapestLocation(): ?bool
    {
        if ($this->price === null) {
            return null;
        }

        $minPrice = self::where('beer_id', $this->beer_id)
            ->whereNotNull('price')
            ->min('price');

        return $this->price <= $minPrice;
    }

    /**
     * Scope para filtrar relaciones destacadas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope para filtrar por rango de precio.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $min
     * @param float|null $max
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePriceRange($query, float $min, ?float $max = null)
    {
        $query = $query->where('price', '>=', $min);

        if ($max !== null) {
            $query = $query->where('price', '<=', $max);
        }

        return $query;
    }

    /**
     * Scope para ordenar por precio.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByPrice($query, string $direction = 'asc')
    {
        return $query->orderBy('price', $direction);
    }
}
