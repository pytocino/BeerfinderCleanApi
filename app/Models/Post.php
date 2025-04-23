<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
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
        'location_id',
        'review',
        'rating',
        'photo_url',
        'additional_photos',
        'serving_type',
        'purchase_price',
        'purchase_currency',
        'user_tags',
        'likes_count',
        'comments_count',
        'edited',
        'edited_at'
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'decimal:1',
        'additional_photos' => 'array',
        'purchase_price' => 'decimal:2',
        'user_tags' => 'array',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'edited' => 'boolean',
        'edited_at' => 'datetime'
    ];

    /**
     * Obtiene el usuario que creó el post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene los comentarios asociados al post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Obtiene los likes asociados al post.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Obtiene la cerveza asociada al post.
     */
    public function beer(): BelongsTo
    {
        return $this->belongsTo(Beer::class);
    }
    /**
     * Obtiene la ubicación asociada al post.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
