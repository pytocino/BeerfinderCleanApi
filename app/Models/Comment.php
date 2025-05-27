<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_comments';

    protected $fillable = [
        'user_id',
        'post_id',
        'content',
        'parent_id',
        'edited',
        'pinned',
        'edited_at',
        'likes_count',
    ];

    protected $casts = [
        'edited' => 'boolean',
        'pinned' => 'boolean',
        'edited_at' => 'datetime',
        'likes_count' => 'integer',
    ];

    /**
     * Obtiene el usuario autor del comentario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el post al que pertenece el comentario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Obtiene el comentario padre (si es una respuesta).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Obtiene las respuestas a este comentario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Verifica si el comentario ha sido editado.
     *
     * @return bool
     */
    public function hasBeenEdited(): bool
    {
        return $this->edited;
    }

    /**
     * Verifica si el comentario está fijado.
     *
     * @return bool
     */
    public function isPinned(): bool
    {
        return $this->pinned;
    }

    /**
     * Verifica si el comentario es una respuesta a otro comentario.
     *
     * @return bool
     */
    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Verifica si el comentario tiene respuestas.
     *
     * @return bool
     */
    public function hasReplies(): bool
    {
        return $this->replies()->count() > 0;
    }

    /**
     * Obtiene el número de respuestas al comentario.
     *
     * @return int
     */
    public function getRepliesCount(): int
    {
        return $this->replies()->count();
    }

    /**
     * Edita el contenido del comentario.
     *
     * @param string $content
     * @return bool
     */
    public function editContent(string $content): bool
    {
        return $this->update([
            'content' => $content,
            'edited' => true,
            'edited_at' => now(),
        ]);
    }

    /**
     * Fija o desfija el comentario.
     *
     * @param bool $pinned
     * @return bool
     */
    public function setPinned(bool $pinned = true): bool
    {
        return $this->update(['pinned' => $pinned]);
    }

    // =====================================================
    // RELACIONES LIKES
    // =====================================================

    /**
     * Obtiene los "me gusta" dados a este comentario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // =====================================================
    // MÉTODOS DE LIKES
    // =====================================================

    /**
     * Verifica si el usuario ha dado "me gusta" a este comentario.
     *
     * @param int $userId
     * @return bool
     */
    public function isLikedByUser(int $userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Alterna el estado de like para este comentario (usar modelo Like).
     *
     * @param int $userId
     * @return bool Verdadero si se añadió like, falso si se eliminó
     */
    public function toggleLike(int $userId): bool
    {
        $wasAdded = Like::toggle($userId, $this);
        
        // Actualizar contador de likes
        $this->updateLikesCount();
        
        return $wasAdded;
    }

    /**
     * Obtiene el conteo actualizado de likes para este comentario.
     *
     * @return int
     */
    public function getLikesCount(): int
    {
        return Like::countFor($this);
    }

    /**
     * Actualiza el contador de likes en el modelo.
     *
     * @return bool
     */
    public function updateLikesCount(): bool
    {
        return $this->update(['likes_count' => $this->getLikesCount()]);
    }

    /**
     * Obtiene los usuarios que dieron like a este comentario.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsersWhoLiked()
    {
        return Like::getUsersWhoLiked($this);
    }
}
