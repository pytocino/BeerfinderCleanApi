<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brewery extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'breweries';

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
}
