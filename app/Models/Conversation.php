<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'user_conversations';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'type',
        'last_message_at',
        'created_by',
        'image_url',
        'description',
        'is_public',
        'group_settings',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_message_at' => 'datetime',
        'is_public' => 'boolean',
        'group_settings' => 'array',
    ];

    /**
     * Tipos de conversación.
     */
    const TYPE_DIRECT = 'direct';
    const TYPE_GROUP = 'group';

    /**
     * Obtiene el usuario que creó la conversación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Obtiene los usuarios participantes en la conversación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot('role', 'joined_at', 'last_read_at', 'is_muted', 'is_admin')
            ->withTimestamps();
    }

    /**
     * Obtiene los mensajes de la conversación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Verifica si la conversación es directa (1 a 1).
     *
     * @return bool
     */
    public function isDirect(): bool
    {
        return $this->type === self::TYPE_DIRECT;
    }

    /**
     * Verifica si la conversación es grupal.
     *
     * @return bool
     */
    public function isGroup(): bool
    {
        return $this->type === self::TYPE_GROUP;
    }

    /**
     * Verifica si la conversación es pública.
     *
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->is_public;
    }

    /**
     * Verifica si un usuario es participante de la conversación.
     *
     * @param int $userId
     * @return bool
     */
    public function hasParticipant(int $userId): bool
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }

    /**
     * Verifica si un usuario es administrador de la conversación.
     *
     * @param int $userId
     * @return bool
     */
    public function isAdmin(int $userId): bool
    {
        return $this->participants()
            ->where('user_id', $userId)
            ->wherePivot('is_admin', true)
            ->exists();
    }

    /**
     * Añade un usuario a la conversación.
     *
     * @param int $userId
     * @param array $attributes
     * @return bool
     */
    public function addParticipant(int $userId, array $attributes = []): bool
    {
        if ($this->hasParticipant($userId)) {
            return false;
        }

        $defaultAttributes = [
            'joined_at' => now(),
            'last_read_at' => now(),
            'is_muted' => false,
            'is_admin' => false,
            'role' => 'member',
        ];

        $this->participants()->attach($userId, array_merge($defaultAttributes, $attributes));
        return true;
    }

    /**
     * Elimina un usuario de la conversación.
     *
     * @param int $userId
     * @return bool
     */
    public function removeParticipant(int $userId): bool
    {
        return (bool) $this->participants()->detach($userId);
    }

    /**
     * Actualiza el último mensaje para la conversación.
     *
     * @return bool
     */
    public function updateLastMessageTime(): bool
    {
        return $this->update(['last_message_at' => now()]);
    }

    /**
     * Obtiene el último mensaje de la conversación.
     *
     * @return Message|null
     */
    public function getLastMessage(): ?Message
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Obtiene los mensajes no leídos para un usuario específico.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnreadMessages(int $userId)
    {
        $lastRead = $this->participants()
            ->where('user_id', $userId)
            ->first()
            ->pivot
            ->last_read_at;

        if (!$lastRead) {
            return $this->messages;
        }

        return $this->messages()
            ->where('created_at', '>', $lastRead)
            ->where('user_id', '!=', $userId)
            ->get();
    }

    /**
     * Marca todos los mensajes como leídos para un usuario.
     *
     * @param int $userId
     * @return bool
     */
    public function markAsRead(int $userId): bool
    {
        return (bool) $this->participants()
            ->where('user_id', $userId)
            ->update(['last_read_at' => now()]);
    }

    /**
     * Actualiza la configuración de un grupo.
     *
     * @param array $settings
     * @return bool
     */
    public function updateGroupSettings(array $settings): bool
    {
        if (!$this->isGroup()) {
            return false;
        }

        $currentSettings = $this->group_settings ?: [];
        $newSettings = array_merge($currentSettings, $settings);

        return $this->update(['group_settings' => $newSettings]);
    }

    /**
     * Obtiene una configuración específica del grupo.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getGroupSetting(string $key, $default = null)
    {
        if (!$this->group_settings) {
            return $default;
        }

        return $this->group_settings[$key] ?? $default;
    }

    /**
     * Scope para filtrar conversaciones directas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDirect($query)
    {
        return $query->where('type', self::TYPE_DIRECT);
    }

    /**
     * Scope para filtrar conversaciones grupales.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGroup($query)
    {
        return $query->where('type', self::TYPE_GROUP);
    }

    /**
     * Scope para filtrar conversaciones públicas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope para filtrar conversaciones con un participante específico.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithParticipant($query, int $userId)
    {
        return $query->whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /**
     * Obtiene el título de la conversación para mostrar a un usuario específico.
     *
     * @param int $userId
     * @return string
     */
    public function getTitleFor(int $userId): string
    {
        // Si es un grupo con título, usar ese
        if ($this->title) {
            return $this->title;
        }

        // Si es conversación directa, mostrar el nombre del otro participante
        if ($this->isDirect()) {
            $otherParticipant = $this->participants()
                ->where('user_id', '!=', $userId)
                ->first();

            return $otherParticipant ? $otherParticipant->name : 'Chat';
        }

        // Fallback para grupos sin título
        return 'Grupo sin nombre';
    }

    /**
     * Obtiene la URL de la imagen para mostrar.
     *
     * @param int|null $userId Para conversaciones directas
     * @return string
     */
    public function getImageUrl(?int $userId = null): string
    {
        // Si es un grupo con imagen, usar esa
        if ($this->image_url) {
            return $this->image_url;
        }

        // Si es conversación directa, mostrar la imagen del otro participante
        if ($this->isDirect() && $userId) {
            $otherParticipant = $this->participants()
                ->where('user_id', '!=', $userId)
                ->first();

            return $otherParticipant && $otherParticipant->profile_picture
                ? $otherParticipant->profile_picture
                : asset('images/default-avatar.png');
        }

        // Fallback para grupos sin imagen
        return asset('images/default-group.png');
    }
}
