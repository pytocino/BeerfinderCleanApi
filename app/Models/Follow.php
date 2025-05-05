<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follow extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'user_follows';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'follower_id',
        'following_id',
        'status',
    ];

    /**
     * Estados posibles para una solicitud de seguimiento.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    /**
     * Obtiene el usuario seguidor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * Obtiene el usuario seguido.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function following(): BelongsTo
    {
        return $this->belongsTo(User::class, 'following_id');
    }

    /**
     * Verifica si la solicitud de seguimiento está pendiente.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Verifica si la solicitud de seguimiento ha sido aceptada.
     *
     * @return bool
     */
    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    /**
     * Verifica si la solicitud de seguimiento ha sido rechazada.
     *
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Acepta una solicitud de seguimiento pendiente.
     *
     * @return bool
     */
    public function accept(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        return $this->update(['status' => self::STATUS_ACCEPTED]);
    }

    /**
     * Rechaza una solicitud de seguimiento pendiente.
     *
     * @return bool
     */
    public function reject(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        return $this->update(['status' => self::STATUS_REJECTED]);
    }

    /**
     * Verifica si el seguimiento ya existe.
     *
     * @param int $followerId
     * @param int $followingId
     * @return bool
     */
    public static function followExists(int $followerId, int $followingId): bool
    {
        return self::where('follower_id', $followerId)
            ->where('following_id', $followingId)
            ->exists();
    }

    /**
     * Crea una nueva relación de seguimiento.
     *
     * @param int $followerId
     * @param int $followingId
     * @param string|null $initialStatus
     * @return Follow|null
     */
    public static function createFollow(int $followerId, int $followingId, ?string $initialStatus = null): ?Follow
    {
        // Evitar que un usuario se siga a sí mismo
        if ($followerId === $followingId) {
            return null;
        }

        // Verificar si ya existe
        if (self::followExists($followerId, $followingId)) {
            return null;
        }

        // Verificar si el usuario a seguir tiene perfil privado
        $followingUser = User::find($followingId);
        $status = $initialStatus ?? ($followingUser && $followingUser->private_profile
            ? self::STATUS_PENDING
            : self::STATUS_ACCEPTED);

        return self::create([
            'follower_id' => $followerId,
            'following_id' => $followingId,
            'status' => $status,
        ]);
    }

    /**
     * Scope para filtrar seguimientos pendientes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope para filtrar seguimientos aceptados.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    /**
     * Scope para filtrar seguimientos rechazados.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope para filtrar por seguidor.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByFollower($query, int $userId)
    {
        return $query->where('follower_id', $userId);
    }

    /**
     * Scope para filtrar por usuario seguido.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByFollowing($query, int $userId)
    {
        return $query->where('following_id', $userId);
    }
}
