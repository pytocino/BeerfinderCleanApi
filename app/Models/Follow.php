<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follow extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'follower_id',
        'following_id',
        'accepted',
        'followed_at',
        'unfollowed_at'
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'accepted' => 'boolean',
        'followed_at' => 'datetime',
        'unfollowed_at' => 'datetime',
    ];

    /**
     * Obtiene el usuario que sigue (el seguidor).
     */
    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * Obtiene el usuario que es seguido.
     */
    public function following(): BelongsTo
    {
        return $this->belongsTo(User::class, 'following_id');
    }

    /**
     * Determina si el seguimiento está activo.
     */
    public function isActive(): bool
    {
        return $this->accepted && is_null($this->unfollowed_at);
    }

    /**
     * Determina si el seguimiento está pendiente.
     */
    public function isPending(): bool
    {
        return !$this->accepted && is_null($this->unfollowed_at);
    }
}
