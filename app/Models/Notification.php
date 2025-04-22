<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', // El usuario que recibe la notificación
        'from_user_id', // El usuario que genera la notificación
        'type', // El tipo de notificación (like, comment, follow, etc.)
        'related_id', // ID de la entidad relacionada (check-in, comentario, etc.)
        'is_read', // Si la notificación ha sido leída
        'data', // Datos adicionales de la notificación
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
    ];

    /**
     * Obtiene el usuario al que pertenece esta notificación.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el usuario que generó esta notificación.
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Marca la notificación como leída.
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }
}
