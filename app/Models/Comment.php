<?php

namespace App\Models;

use App\Traits\Reportable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory, Reportable;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'post_id',      // Añadido para asignación masiva
        'content',
        'parent_id',
        'edited',
        'edited_at',
        'pinned',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'edited' => 'boolean',
        'edited_at' => 'datetime',
        'pinned' => 'boolean',
    ];

    /**
     * Obtiene el usuario que realizó el comentario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el post.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Obtiene el comentario padre (si es una respuesta).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Obtiene las respuestas a este comentario.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Determina si este comentario es una respuesta a otro.
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * Determina si este comentario está fijado.
     */
    public function isPinned(): bool
    {
        return $this->pinned;
    }
}
