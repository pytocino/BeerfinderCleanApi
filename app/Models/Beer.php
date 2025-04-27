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
        'description',
        'brewery',
        'style_id',
        'abv',
        'ibu',
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
     * Obtiene el estilo asociado a esta cerveza.
     */
    public function style(): BelongsTo
    {
        return $this->belongsTo(BeerStyle::class);
    }

    /**
     * Obtiene los check-ins que tiene esta cerveza.
     */
    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }
}
