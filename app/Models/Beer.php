<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'brewery_id',
        'style_id',
        'abv',
        'ibu',
        'description',
        'image_url',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'abv' => 'decimal:2',
        'ibu' => 'integer',
    ];

    /**
     * Obtiene la cervecería asociada a esta cerveza.
     */
    public function brewery(): BelongsTo
    {
        return $this->belongsTo(Brewery::class);
    }

    /**
     * Obtiene el estilo asociado a esta cerveza.
     */
    public function style(): BelongsTo
    {
        return $this->belongsTo(BeerStyle::class);
    }

    /**
     * Obtiene los check-ins asociados a esta cerveza.
     */
    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }

    /**
     * Obtiene los usuarios que han marcado esta cerveza como favorita.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}
