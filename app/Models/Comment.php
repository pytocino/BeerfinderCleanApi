<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'user_comments';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
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

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
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

    /**
     * Incrementa el contador de likes.
     *
     * @return bool
     */
    public function incrementLikes(): bool
    {
        return $this->increment('likes_count');
    }

    /**
     * Decrementa el contador de likes.
     *
     * @return bool
     */
    public function decrementLikes(): bool
    {
        if ($this->likes_count > 0) {
            return $this->decrement('likes_count');
        }
        return false;
    }

    /**
     * Scope para filtrar comentarios de nivel superior (no respuestas).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope para filtrar comentarios fijados.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePinned($query)
    {
        return $query->where('pinned', true);
    }

    /**
     * Scope para filtrar por post.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $postId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPost($query, int $postId)
    {
        return $query->where('post_id', $postId);
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
     * Scope para ordenar por cantidad de likes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByLikes($query)
    {
        return $query->orderBy('likes_count', 'desc');
    }

    /**
     * Scope para filtrar respuestas a un comentario específico.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $parentId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRepliesTo($query, int $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    /**
     * Obtiene el texto truncado para vista previa.
     *
     * @param int $length
     * @return string
     */
    public function getExcerpt(int $length = 100): string
    {
        if (strlen($this->content) <= $length) {
            return $this->content;
        }

        return substr($this->content, 0, $length) . '...';
    }
}
