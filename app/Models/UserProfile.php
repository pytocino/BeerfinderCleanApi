<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bio',
        'location',
        'birthdate',
        'website',
        'phone',
        'instagram',
        'twitter',
        'facebook',
        'allow_mentions',
        'show_online_status',
        'share_location',
        'email_notifications',
        'notify_new_followers',
        'notify_likes',
        'notify_comments',
        'notify_mentions',
        'notify_following_posts',
        'notify_recommendations',
        'notify_trends',
        'notify_direct_messages',
        'notify_group_messages',
        'notify_events',
        'notify_updates',
        'notify_security',
        'notify_promotions',
        'timezone',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birthdate' => 'date',
        'allow_mentions' => 'boolean',
        'show_online_status' => 'boolean',
        'share_location' => 'boolean',
        'email_notifications' => 'boolean',
        'notify_new_followers' => 'boolean',
        'notify_likes' => 'boolean',
        'notify_comments' => 'boolean',
        'notify_mentions' => 'boolean',
        'notify_following_posts' => 'boolean',
        'notify_recommendations' => 'boolean',
        'notify_trends' => 'boolean',
        'notify_direct_messages' => 'boolean',
        'notify_group_messages' => 'boolean',
        'notify_events' => 'boolean',
        'notify_updates' => 'boolean',
        'notify_security' => 'boolean',
        'notify_promotions' => 'boolean',
    ];

    /**
     * Relación con el usuario propietario del perfil.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determina si el usuario cumple años hoy.
     *
     * @return bool
     */
    public function isBirthday(): bool
    {
        if (!$this->birthdate) {
            return false;
        }

        return $this->birthdate->isBirthday();
    }

    /**
     * Obtiene la edad del usuario.
     *
     * @return int|null
     */
    public function getAge(): ?int
    {
        return $this->birthdate ? $this->birthdate->age : null;
    }

    /**
     * Determina si el usuario tiene redes sociales configuradas.
     *
     * @return bool
     */
    public function hasSocialLinks(): bool
    {
        return $this->instagram || $this->twitter || $this->facebook;
    }

    /**
     * Obtiene todas las redes sociales como un array.
     *
     * @return array
     */
    public function getSocialLinks(): array
    {
        $links = [];

        if ($this->instagram) {
            $links['instagram'] = $this->instagram;
        }

        if ($this->twitter) {
            $links['twitter'] = $this->twitter;
        }

        if ($this->facebook) {
            $links['facebook'] = $this->facebook;
        }

        return $links;
    }

    /**
     * Formatea el número de teléfono para visualización.
     * 
     * @return string|null
     */
    public function getFormattedPhone(): ?string
    {
        // Puedes implementar cualquier formato específico que necesites
        return $this->phone;
    }

    /**
     * Verificar si las notificaciones por email están habilitadas.
     *
     * @return bool
     */
    public function hasEmailNotificationsEnabled(): bool
    {
        return $this->email_notifications;
    }

    /**
     * Verificar si las menciones están permitidas.
     *
     * @return bool
     */
    public function allowsMentions(): bool
    {
        return $this->allow_mentions;
    }
}
