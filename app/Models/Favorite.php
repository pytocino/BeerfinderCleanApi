<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'beer_id',
    ];

    /**
     * Obtiene el usuario al que pertenece este favorito.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la cerveza marcada como favorita.
     */
    public function beer(): BelongsTo
    {
        return $this->belongsTo(Beer::class);
    }

    /**
     * Verifica si un usuario ya tiene una cerveza como favorita.
     *
     * @param int $userId
     * @param int $beerId
     * @return bool
     */
    public static function isFavorite(int $userId, int $beerId): bool
    {
        return self::where('user_id', $userId)
            ->where('beer_id', $beerId)
            ->exists();
    }
}
