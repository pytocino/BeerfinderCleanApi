<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
        'country',
        'city',
        'address',
        'latitude',
        'longitude',
        'description',
        'logo_url',
        'website',
        'email',
        'phone',
        'instagram',
        'facebook',
        'twitter',
        'cover_photo',
        'founded'
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'founded' => 'integer',
    ];

    /**
     * Obtiene las cervezas asociadas a esta cervecería.
     */
    public function beers(): HasMany
    {
        return $this->hasMany(Beer::class);
    }
}
