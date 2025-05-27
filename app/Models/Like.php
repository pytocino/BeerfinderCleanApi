<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    use HasFactory;

    protected $table = 'user_likes';

    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type',
    ];

    // =====================================================
    // RELACIONES
    // =====================================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    // =====================================================
    // MÉTODOS PRINCIPALES
    // =====================================================

    /**
     * Busca un like específico para usuario y elemento
     */
    private static function findLike(int $userId, Model $likeable): ?self
    {
        return self::where('user_id', $userId)
            ->where('likeable_id', $likeable->id)
            ->where('likeable_type', get_class($likeable))
            ->first();
    }

    /**
     * Alterna el estado de like (crea o elimina)
     */
    public static function toggle(int $userId, Model $likeable): bool
    {
        $like = self::findLike($userId, $likeable);
        
        if ($like) {
            $like->delete();
            return false; // Se eliminó
        }
        
        self::create([
            'user_id' => $userId,
            'likeable_id' => $likeable->id,
            'likeable_type' => get_class($likeable),
        ]);
        
        return true; // Se añadió
    }

    /**
     * Cuenta likes para un elemento
     */
    public static function countFor(Model $likeable): int
    {
        return self::where('likeable_id', $likeable->id)
            ->where('likeable_type', get_class($likeable))
            ->count();
    }

    /**
     * Obtiene los usuarios que dieron like a un elemento específico
     */
    public static function getUsersWhoLiked(Model $likeable)
    {
        return User::whereIn('id', function($query) use ($likeable) {
            $query->select('user_id')
                  ->from('user_likes')
                  ->where('likeable_id', $likeable->id)
                  ->where('likeable_type', get_class($likeable));
        })->get();
    }
}
