<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'profile_picture',
        'last_active_at',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_admin' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_active_at' => 'datetime',
    ];

    /**
     * Get the correct count of followers (only accepted status)
     */
    public function followersCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Si ya tenemos el conteo, lo usamos
                if (array_key_exists('followers_count', $this->attributes)) {
                    return $this->attributes['followers_count'];
                }

                // Si tenemos la relación cargada, contamos solo los aceptados
                if ($this->relationLoaded('followers')) {
                    return $this->followers->where('pivot.status', '=', 'accepted')->count();
                }

                // Por último, hacemos la consulta directa
                return $this->acceptedFollowers()->count();
            },
        );
    }

    /**
     * Get the correct count of following (only accepted status)
     */
    public function followingCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Si ya tenemos el conteo, lo usamos
                if (array_key_exists('following_count', $this->attributes)) {
                    return $this->attributes['following_count'];
                }

                // Si tenemos la relación cargada, contamos solo los aceptados
                if ($this->relationLoaded('following')) {
                    return $this->following->where('pivot.status', '=', 'accepted')->count();
                }

                // Por último, hacemos la consulta directa
                return $this->acceptedFollowing()->count();
            },
        );
    }

    /**
     * Relación con el perfil extendido.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Usuarios a los que este usuario sigue (con status).
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Usuarios a los que este usuario sigue con estado aceptado.
     */
    public function acceptedFollowing(): BelongsToMany
    {
        return $this->following()->wherePivot('status', '=', 'accepted');
    }

    /**
     * Usuarios que siguen a este usuario (con status).
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Usuarios que siguen a este usuario con estado aceptado.
     */
    public function acceptedFollowers(): BelongsToMany
    {
        return $this->followers()->wherePivot('status', '=', 'accepted');
    }

    /**
     * Posts del usuario.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Notificaciones del usuario.
     */
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable');
    }

    /**
     * Check-ins realizados por el usuario.
     */
    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }

    /**
     * Conversaciones del usuario.
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user');
    }
}
