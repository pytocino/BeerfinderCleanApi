<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'user_posts';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'content',
        'photo_url',
        'additional_photos',
        'user_tags',
        'edited',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'additional_photos' => 'array',
        'user_tags' => 'array',
        'edited' => 'boolean',
    ];

    /**
     * Obtiene el usuario autor del post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene los comentarios del post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Obtiene los likes del post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Obtiene la reseña de cerveza asociada (si existe).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function beerReview(): HasOne
    {
        return $this->hasOne(BeerReview::class);
    }

    /**
     * Obtiene la cerveza asociada al post (si existe).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function beer()
    {
        return $this->belongsTo(Beer::class);
    }

    /**
     * Obtiene la localización asociada al post (si existe).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Verifica si el post tiene una imagen principal.
     *
     * @return bool
     */
    public function hasPhoto(): bool
    {
        return !empty($this->photo_url);
    }

    /**
     * Verifica si el post tiene imágenes adicionales.
     *
     * @return bool
     */
    public function hasAdditionalPhotos(): bool
    {
        return !empty($this->additional_photos);
    }

    /**
     * Obtiene el número total de imágenes (principal + adicionales).
     *
     * @return int
     */
    public function getTotalPhotosCount(): int
    {
        $count = $this->hasPhoto() ? 1 : 0;
        $additional = $this->additional_photos;

        if (is_string($additional)) {
            $additional = json_decode($additional, true) ?: [];
        } elseif (!is_array($additional)) {
            $additional = [];
        }

        return $count + count($additional);
    }

    public function getAllPhotos(): array
    {
        $photos = [];

        if ($this->hasPhoto()) {
            $photos[] = $this->photo_url;
        }

        if ($this->hasAdditionalPhotos()) {
            $additional = is_array($this->additional_photos)
                ? $this->additional_photos
                : (is_string($this->additional_photos) ? json_decode($this->additional_photos, true) : []);
            $photos = array_merge($photos, $additional ?: []);
        }

        return $photos;
    }

    /**
     * Verifica si un usuario específico ha dado like al post.
     *
     * @param int $userId
     * @return bool
     */
    public function isLikedBy(int $userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Obtiene la cantidad de likes.
     *
     * @return int
     */
    public function getLikesCount(): int
    {
        return $this->likes()->count();
    }

    /**
     * Obtiene la cantidad de comentarios.
     *
     * @return int
     */
    public function getCommentsCount(): int
    {
        return $this->comments()->count();
    }

    /**
     * Verifica si el post tiene usuarios etiquetados.
     *
     * @return bool
     */
    public function hasUserTags(): bool
    {
        return !empty($this->user_tags);
    }

    /**
     * Obtiene los usuarios etiquetados en el post.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTaggedUsers()
    {
        if (!$this->hasUserTags()) {
            return collect();
        }

        return User::whereIn('id', $this->user_tags)->get();
    }

    /**
     * Añade una etiqueta de usuario al post.
     *
     * @param int $userId
     * @return bool
     */
    public function addUserTag(int $userId): bool
    {
        if (!User::find($userId)) {
            return false;
        }

        $tags = $this->user_tags ?: [];

        if (!in_array($userId, $tags)) {
            $tags[] = $userId;
            $this->user_tags = $tags;
            return $this->save();
        }

        return true;
    }

    /**
     * Elimina una etiqueta de usuario del post.
     *
     * @param int $userId
     * @return bool
     */
    public function removeUserTag(int $userId): bool
    {
        if (!$this->hasUserTags()) {
            return true;
        }

        $tags = array_filter($this->user_tags, function ($tagId) use ($userId) {
            return $tagId != $userId;
        });

        $this->user_tags = array_values($tags);
        return $this->save();
    }

    /**
     * Scope para obtener posts con imágenes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPhotos($query)
    {
        return $query->whereNotNull('photo_url');
    }

    /**
     * Scope para obtener posts con etiquetas de usuario.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithUserTags($query)
    {
        return $query->whereNotNull('user_tags')
            ->whereRaw("JSON_LENGTH(user_tags) > 0");
    }

    /**
     * Scope para obtener posts que mencionen a un usuario específico.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTagging($query, int $userId)
    {
        return $query->whereRaw("JSON_CONTAINS(user_tags, ?)", $userId);
    }

    /**
     * Scope para ordenar por popularidad (likes + comentarios).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByPopularity($query)
    {
        return $query->withCount(['likes', 'comments'])
            ->orderByRaw('likes_count + comments_count DESC');
    }
}
