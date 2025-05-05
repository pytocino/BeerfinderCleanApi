<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'favorite_beers';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'favorable_id',
        'favorable_type',
    ];

    /**
     * Obtiene el usuario que marcó como favorito.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el elemento marcado como favorito (polimórfico).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function favorable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Verifica si un elemento específico ya está marcado como favorito por un usuario.
     *
     * @param int $userId
     * @param Model $favorable
     * @return bool
     */
    public static function isFavorite(int $userId, Model $favorable): bool
    {
        return self::where('user_id', $userId)
            ->where('favorable_id', $favorable->id)
            ->where('favorable_type', get_class($favorable))
            ->exists();
    }

    /**
     * Marca un elemento como favorito para un usuario.
     *
     * @param int $userId
     * @param Model $favorable
     * @return Favorite|null
     */
    public static function add(int $userId, Model $favorable): ?Favorite
    {
        if (self::isFavorite($userId, $favorable)) {
            return null;
        }

        return self::create([
            'user_id' => $userId,
            'favorable_id' => $favorable->id,
            'favorable_type' => get_class($favorable),
        ]);
    }

    /**
     * Elimina un elemento de favoritos para un usuario.
     *
     * @param int $userId
     * @param Model $favorable
     * @return bool
     */
    public static function remove(int $userId, Model $favorable): bool
    {
        return (bool) self::where('user_id', $userId)
            ->where('favorable_id', $favorable->id)
            ->where('favorable_type', get_class($favorable))
            ->delete();
    }

    /**
     * Alterna el estado de favorito de un elemento para un usuario.
     *
     * @param int $userId
     * @param Model $favorable
     * @return bool True si se añadió, False si se eliminó
     */
    public static function toggle(int $userId, Model $favorable): bool
    {
        if (self::isFavorite($userId, $favorable)) {
            self::remove($userId, $favorable);
            return false;
        } else {
            self::add($userId, $favorable);
            return true;
        }
    }

    /**
     * Scope para filtrar por tipo de elemento favorito.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('favorable_type', $type);
    }

    /**
     * Scope para filtrar por usuario.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para ordenar por más recientes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Obtiene todos los favoritos de un tipo específico para un usuario.
     *
     * @param int $userId
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUserFavoritesByType(int $userId, string $type)
    {
        return self::byUser($userId)->ofType($type)->latest()->get();
    }

    /**
     * Obtiene todos los IDs de elementos favoritos de un tipo específico para un usuario.
     *
     * @param int $userId
     * @param string $type
     * @return array
     */
    public static function getUserFavoriteIds(int $userId, string $type): array
    {
        return self::byUser($userId)
            ->ofType($type)
            ->pluck('favorable_id')
            ->toArray();
    }
}
