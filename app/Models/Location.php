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
        'verified',
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

    /**
     * Devuelve las cervezas distintas que se han tomado en esta ubicación.
     */
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
