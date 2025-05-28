<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne, MorphMany};
use Illuminate\Database\Eloquent\SoftDeletes;

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
     */
    protected $fillable = [
        'user_id',
        'content',
        'photo_url',
        'additional_photos',
        'tags', // Cambió de user_tags a tags para soportar usuarios y cervezas
        'edited',
        'beer_id',
        'location_id',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     */
    protected $casts = [
        'additional_photos' => 'array',
        'tags' => 'array', // Cambió de user_tags a tags
        'edited' => 'boolean',
    ];

    /**
     * Obtiene el usuario autor del post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene los comentarios del post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Obtiene los likes del post.
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Obtiene la reseña de cerveza asociada (si existe).
     */
    public function beerReview(): HasOne
    {
        return $this->hasOne(BeerReview::class);
    }

    /**
     * Obtiene la cerveza asociada al post (si existe).
     */
    public function beer(): BelongsTo
    {
        return $this->belongsTo(Beer::class);
    }

    /**
     * Obtiene la localización asociada al post (si existe).
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Verifica si el post tiene una imagen principal.
     */
    public function hasPhoto(): bool
    {
        return !empty($this->photo_url);
    }

    /**
     * Verifica si el post tiene imágenes adicionales.
     */
    public function hasAdditionalPhotos(): bool
    {
        return !empty($this->additional_photos);
    }

    /**
     * Obtiene todas las fotos del post.
     */
    public function getAllPhotos(): array
    {
        $photos = [];
        
        if ($this->hasPhoto()) {
            $photos[] = $this->photo_url;
        }

        if ($this->hasAdditionalPhotos()) {
            $photos = array_merge($photos, $this->additional_photos);
        }

        return $photos;
    }

    /**
     * Obtiene el número total de imágenes.
     */
    public function getTotalPhotosCount(): int
    {
        return count($this->getAllPhotos());
    }

    /**
     * Verifica si un usuario específico ha dado like al post.
     */
    public function isLikedBy(int $userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Verifica si el post tiene etiquetas.
     */
    public function hasTags(): bool
    {
        return !empty($this->tags);
    }

    /**
     * Verifica si el post tiene usuarios etiquetados.
     */
    public function hasUserTags(): bool
    {
        if (!$this->hasTags()) {
            return false;
        }

        return collect($this->tags)->contains('type', 'user');
    }

    /**
     * Verifica si el post tiene cervezas etiquetadas.
     */
    public function hasBeerTags(): bool
    {
        if (!$this->hasTags()) {
            return false;
        }

        return collect($this->tags)->contains('type', 'beer');
    }

    /**
     * Verifica si el post tiene ubicaciones etiquetadas.
     */
    public function hasLocationTags(): bool
    {
        if (!$this->hasTags()) {
            return false;
        }

        return collect($this->tags)->contains('type', 'location');
    }

    /**
     * Obtiene los usuarios etiquetados en el post.
     */
    public function getTaggedUsers(): Collection
    {
        if (!$this->hasUserTags()) {
            return collect();
        }

        $userIds = collect($this->tags)
            ->where('type', 'user')
            ->pluck('id')
            ->toArray();

        return User::whereIn('id', $userIds)->get();
    }

    /**
     * Obtiene las cervezas etiquetadas en el post.
     */
    public function getTaggedBeers(): Collection
    {
        if (!$this->hasBeerTags()) {
            return collect();
        }

        $beerIds = collect($this->tags)
            ->where('type', 'beer')
            ->pluck('id')
            ->toArray();

        return Beer::with(['brewery', 'style'])->whereIn('id', $beerIds)->get();
    }

    /**
     * Obtiene las ubicaciones etiquetadas en el post.
     */
    public function getTaggedLocations(): Collection
    {
        if (!$this->hasLocationTags()) {
            return collect();
        }

        $locationIds = collect($this->tags)
            ->where('type', 'location')
            ->pluck('id')
            ->toArray();

        return Location::whereIn('id', $locationIds)->get();
    }

    /**
     * Obtiene todas las etiquetas organizadas por tipo.
     */
    public function getOrganizedTags(): array
    {
        if (!$this->hasTags()) {
            return [
                'users' => collect(),
                'beers' => collect(),
                'locations' => collect(),
            ];
        }

        return [
            'users' => $this->getTaggedUsers(),
            'beers' => $this->getTaggedBeers(),
            'locations' => $this->getTaggedLocations(),
        ];
    }

    /**
     * Añade una etiqueta de usuario al post.
     */
    public function addUserTag(int $userId): bool
    {
        if (!User::find($userId)) {
            return false;
        }

        $tags = $this->tags ?? [];
        $userTag = ['type' => 'user', 'id' => $userId];

        // Verificar si ya existe la etiqueta
        $exists = collect($tags)->contains(function ($tag) use ($userId) {
            return $tag['type'] === 'user' && $tag['id'] === $userId;
        });

        if (!$exists) {
            $tags[] = $userTag;
            $this->tags = $tags;
            return $this->save();
        }

        return true;
    }

    /**
     * Añade una etiqueta de cerveza al post.
     */
    public function addBeerTag(int $beerId): bool
    {
        if (!Beer::find($beerId)) {
            return false;
        }

        $tags = $this->tags ?? [];
        $beerTag = ['type' => 'beer', 'id' => $beerId];

        // Verificar si ya existe la etiqueta
        $exists = collect($tags)->contains(function ($tag) use ($beerId) {
            return $tag['type'] === 'beer' && $tag['id'] === $beerId;
        });

        if (!$exists) {
            $tags[] = $beerTag;
            $this->tags = $tags;
            return $this->save();
        }

        return true;
    }

    /**
     * Añade una etiqueta de ubicación al post.
     */
    public function addLocationTag(int $locationId): bool
    {
        if (!Location::find($locationId)) {
            return false;
        }

        $tags = $this->tags ?? [];
        $locationTag = ['type' => 'location', 'id' => $locationId];

        // Verificar si ya existe la etiqueta
        $exists = collect($tags)->contains(function ($tag) use ($locationId) {
            return $tag['type'] === 'location' && $tag['id'] === $locationId;
        });

        if (!$exists) {
            $tags[] = $locationTag;
            $this->tags = $tags;
            return $this->save();
        }

        return true;
    }

    /**
     * Elimina una etiqueta de usuario del post.
     */
    public function removeUserTag(int $userId): bool
    {
        if (!$this->hasTags()) {
            return true;
        }

        $tags = collect($this->tags)->filter(function ($tag) use ($userId) {
            return !($tag['type'] === 'user' && $tag['id'] === $userId);
        })->values()->toArray();

        $this->tags = $tags;
        return $this->save();
    }

    /**
     * Elimina una etiqueta de cerveza del post.
     */
    public function removeBeerTag(int $beerId): bool
    {
        if (!$this->hasTags()) {
            return true;
        }

        $tags = collect($this->tags)->filter(function ($tag) use ($beerId) {
            return !($tag['type'] === 'beer' && $tag['id'] === $beerId);
        })->values()->toArray();

        $this->tags = $tags;
        return $this->save();
    }

    /**
     * Elimina una etiqueta de ubicación del post.
     */
    public function removeLocationTag(int $locationId): bool
    {
        if (!$this->hasTags()) {
            return true;
        }

        $tags = collect($this->tags)->filter(function ($tag) use ($locationId) {
            return !($tag['type'] === 'location' && $tag['id'] === $locationId);
        })->values()->toArray();

        $this->tags = $tags;
        return $this->save();
    }

    /**
     * Scope para obtener posts con imágenes.
     */
    public function scopeWithPhotos($query)
    {
        return $query->whereNotNull('photo_url');
    }

    /**
     * Scope para obtener posts con etiquetas.
     */
    public function scopeWithTags($query)
    {
        return $query->whereNotNull('tags')
            ->whereJsonLength('tags', '>', 0);
    }

    /**
     * Scope para obtener posts con etiquetas de usuario.
     */
    public function scopeWithUserTags($query)
    {
        return $query->whereNotNull('tags')
            ->whereJsonContains('tags->type', 'user');
    }

    /**
     * Scope para obtener posts con etiquetas de cerveza.
     */
    public function scopeWithBeerTags($query)
    {
        return $query->whereNotNull('tags')
            ->whereJsonContains('tags->type', 'beer');
    }

    /**
     * Scope para obtener posts con etiquetas de ubicación.
     */
    public function scopeWithLocationTags($query)
    {
        return $query->whereNotNull('tags')
            ->whereJsonContains('tags->type', 'location');
    }

    /**
     * Scope para obtener posts que mencionen a un usuario específico.
     */
    public function scopeTaggingUser($query, int $userId)
    {
        return $query->whereJsonContains('tags', ['type' => 'user', 'id' => $userId]);
    }

    /**
     * Scope para obtener posts que mencionen a una cerveza específica.
     */
    public function scopeTaggingBeer($query, int $beerId)
    {
        return $query->whereJsonContains('tags', ['type' => 'beer', 'id' => $beerId]);
    }

    /**
     * Scope para obtener posts que mencionen a una ubicación específica.
     */
    public function scopeTaggingLocation($query, int $locationId)
    {
        return $query->whereJsonContains('tags', ['type' => 'location', 'id' => $locationId]);
    }

    /**
     * Scope para ordenar por popularidad (likes + comentarios).
     */
    public function scopeOrderByPopularity($query)
    {
        return $query->withCount(['likes', 'comments'])
            ->orderByRaw('(likes_count + comments_count) DESC');
    }
}
