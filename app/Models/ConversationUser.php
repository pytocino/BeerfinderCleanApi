<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ConversationUser extends Pivot
{
    use HasFactory;

    /**
     * Indica si la tabla tiene una clave primaria auto-incremental.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'conversation_users';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'conversation_id',
        'user_id',
        'last_read_at',
        'is_muted',
        'joined_at',
        'left_at',
        'role',
        'can_add_members',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_read_at' => 'datetime',
        'is_muted' => 'boolean',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'can_add_members' => 'boolean',
    ];

    /**
     * Roles disponibles para los participantes.
     */
    const ROLE_MEMBER = 'member';
    const ROLE_ADMIN = 'admin';
    const ROLE_OWNER = 'owner';

    /**
     * Obtiene la conversación a la que pertenece esta relación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Obtiene el usuario participante.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica si el usuario ha dejado la conversación.
     *
     * @return bool
     */
    public function hasLeft(): bool
    {
        return $this->left_at !== null;
    }

    /**
     * Verifica si el usuario tiene el rol de miembro.
     *
     * @return bool
     */
    public function isMember(): bool
    {
        return $this->role === self::ROLE_MEMBER;
    }

    /**
     * Verifica si el usuario tiene el rol de administrador.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Verifica si el usuario tiene el rol de propietario.
     *
     * @return bool
     */
    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    /**
     * Verifica si el usuario tiene silenciada la conversación.
     *
     * @return bool
     */
    public function isMuted(): bool
    {
        return $this->is_muted;
    }

    /**
     * Verifica si el usuario puede añadir miembros a la conversación.
     *
     * @return bool
     */
    public function canAddMembers(): bool
    {
        return $this->can_add_members || $this->isAdmin() || $this->isOwner();
    }

    /**
     * Marca todos los mensajes como leídos para este usuario.
     *
     * @return bool
     */
    public function markAsRead(): bool
    {
        return $this->update(['last_read_at' => now()]);
    }

    /**
     * Silencia o activa las notificaciones para este usuario.
     *
     * @param bool $muted
     * @return bool
     */
    public function setMuted(bool $muted = true): bool
    {
        return $this->update(['is_muted' => $muted]);
    }

    /**
     * Establece el rol del usuario en la conversación.
     *
     * @param string $role
     * @return bool
     */
    public function setRole(string $role): bool
    {
        if (!in_array($role, [self::ROLE_MEMBER, self::ROLE_ADMIN, self::ROLE_OWNER])) {
            return false;
        }

        return $this->update(['role' => $role]);
    }

    /**
     * Establece si el usuario puede añadir miembros.
     *
     * @param bool $canAdd
     * @return bool
     */
    public function setCanAddMembers(bool $canAdd = true): bool
    {
        return $this->update(['can_add_members' => $canAdd]);
    }

    /**
     * Marca al usuario como salido de la conversación.
     *
     * @return bool
     */
    public function markAsLeft(): bool
    {
        return $this->update(['left_at' => now()]);
    }

    /**
     * Vuelve a incluir al usuario en la conversación.
     *
     * @return bool
     */
    public function rejoin(): bool
    {
        return $this->update(['left_at' => null, 'joined_at' => now()]);
    }

    /**
     * Obtiene la cantidad de mensajes no leídos para este usuario.
     *
     * @return int
     */
    public function getUnreadCount(): int
    {
        $query = Message::where('conversation_id', $this->conversation_id);

        if ($this->last_read_at) {
            $query->where('created_at', '>', $this->last_read_at);
        }

        $query->where('user_id', '!=', $this->user_id);

        return $query->count();
    }

    /**
     * Scope para filtrar participantes activos (no han dejado la conversación).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNull('left_at');
    }

    /**
     * Scope para filtrar por rol.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope para filtrar administradores y propietarios.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmins($query)
    {
        return $query->whereIn('role', [self::ROLE_ADMIN, self::ROLE_OWNER]);
    }

    /**
     * Scope para filtrar por conversación.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $conversationId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInConversation($query, int $conversationId)
    {
        return $query->where('conversation_id', $conversationId);
    }

    /**
     * Scope para filtrar por usuario.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
