<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'user_likes';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type',
    ];

    /**
     * Obtiene el usuario que dio like.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el elemento al que se dio like (polimórfico).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Verifica si un elemento específico ya tiene like de un usuario.
     *
     * @param int $userId
     * @param Model $likeable
     * @return bool
     */
    public static function hasLike(int $userId, Model $likeable): bool
    {
        return self::where('user_id', $userId)
            ->where('likeable_id', $likeable->id)
            ->where('likeable_type', get_class($likeable))
            ->exists();
    }

    /**
     * Da like a un elemento.
     *
     * @param int $userId
     * @param Model $likeable
     * @return Like|null Retorna null si ya existe
     */
    public static function addLike(int $userId, Model $likeable): ?Like
    {
        if (self::hasLike($userId, $likeable)) {
            return null;
        }

        $like = self::create([
            'user_id' => $userId,
            'likeable_id' => $likeable->id,
            'likeable_type' => get_class($likeable),
        ]);

        // Actualiza contador si el modelo tiene el campo likes_count
        if (method_exists($likeable, 'incrementLikes')) {
            $likeable->incrementLikes();
        } elseif (isset($likeable->likes_count)) {
            $likeable->increment('likes_count');
        }

        return $like;
    }

    /**
     * Quita un like de un elemento.
     *
     * @param int $userId
     * @param Model $likeable
     * @return bool
     */
    public static function removeLike(int $userId, Model $likeable): bool
    {
        $removed = (bool) self::where('user_id', $userId)
            ->where('likeable_id', $likeable->id)
            ->where('likeable_type', get_class($likeable))
            ->delete();

        if ($removed) {
            // Actualiza contador si el modelo tiene el campo likes_count
            if (method_exists($likeable, 'decrementLikes')) {
                $likeable->decrementLikes();
            } elseif (isset($likeable->likes_count) && $likeable->likes_count > 0) {
                $likeable->decrement('likes_count');
            }
        }

        return $removed;
    }

    /**
     * Alterna el estado de like de un elemento.
     *
     * @param int $userId
     * @param Model $likeable
     * @return bool True si se añadió, False si se eliminó
     */
    public static function toggle(int $userId, Model $likeable): bool
    {
        if (self::hasLike($userId, $likeable)) {
            self::removeLike($userId, $likeable);
            return false;
        } else {
            self::addLike($userId, $likeable);
            return true;
        }
    }

    /**
     * Obtiene el recuento de likes para un elemento específico.
     *
     * @param Model $likeable
     * @return int
     */
    public static function countFor(Model $likeable): int
    {
        return self::where('likeable_id', $likeable->id)
            ->where('likeable_type', get_class($likeable))
            ->count();
    }

    /**
     * Scope para filtrar por tipo de elemento.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type Nombre de clase completo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('likeable_type', $type);
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
     * Scope para filtrar por elemento específico.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Model $likeable
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForLikeable($query, Model $likeable)
    {
        return $query->where('likeable_id', $likeable->id)
            ->where('likeable_type', get_class($likeable));
    }

    /**
     * Obtiene los usuarios que han dado like a un elemento específico.
     *
     * @param Model $likeable
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUsersWhoLiked(Model $likeable, int $limit = null)
    {
        $query = User::whereHas('likes', function ($query) use ($likeable) {
            $query->where('likeable_id', $likeable->id)
                ->where('likeable_type', get_class($likeable));
        });

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }
}
