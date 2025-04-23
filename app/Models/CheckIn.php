<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CheckIn extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'post_id',
        'beer_id',
        'location_id',
        'rating',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'decimal:1',
    ];

    /**
     * Las relaciones que siempre deben cargarse.
     *
     * @var array
     */
    protected $with = ['user', 'beer'];

    /**
     * Obtiene el usuario que realizó el check-in.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la cerveza asociada al check-in.
     */
    public function beer(): BelongsTo
    {
        return $this->belongsTo(Beer::class);
    }

    /**
     * Obtiene la ubicación donde se realizó el check-in.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Obtiene el post asociado al check-in.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
