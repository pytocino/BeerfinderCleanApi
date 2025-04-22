<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
        'color',
        'abv_min',
        'abv_max',
        'ibu_min',
        'ibu_max',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'abv_min' => 'decimal:2',
        'abv_max' => 'decimal:2',
        'ibu_min' => 'integer',
        'ibu_max' => 'integer',
    ];

    /**
     * Obtiene las cervezas asociadas a este estilo.
     */
    public function beers(): HasMany
    {
        return $this->hasMany(Beer::class, 'style_id');
    }
}
