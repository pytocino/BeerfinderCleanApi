<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Location extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'country',
        'city',
        'address',
        'latitude',
        'longitude',
        'description',
        'image_url',
        'cover_photo',
        'website',
        'email',
        'phone',
        'instagram',
        'facebook',
        'twitter',
        'verified',
        'check_ins_count'
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'verified' => 'boolean',
        'check_ins_count' => 'integer'
    ];

    /**
     * Obtiene los check-ins realizados en esta ubicación.
     */
    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }

    /**
     * Obtiene la cantidad de check-ins realizados en esta ubicación.
     */
    public function getCheckInsCountAttribute(): int
    {
        return DB::table('check_ins')
            ->where('location_id', $this->id)
            ->count();
    }

    public function beersTasted()
    {
        // Devuelve las cervezas distintas que se han tomado en esta ubicación
        return $this->hasMany(CheckIn::class)
            ->select('beer_id')
            ->whereNotNull('beer_id')
            ->groupBy('beer_id');
    }

    // Si quieres obtener los modelos Beer directamente:
    public function uniqueBeers()
    {
        return $this->belongsToMany(
            Beer::class,
            'check_ins',
            'location_id',
            'beer_id'
        )->distinct();
    }
}
