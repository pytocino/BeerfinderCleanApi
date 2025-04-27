<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
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
    ];

    /**
     * Obtiene el usuario que dio like.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el post al que pertenece este like.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Verifica si un usuario ya dio like a un post específico.
     *
     * @param int $userId
     * @param int $postId
     * @return bool
     */
    public static function isLiked(int $userId, int $postId): bool
    {
        return self::where('user_id', $userId)
            ->where('post_id', $postId)
            ->exists();
    }
}
