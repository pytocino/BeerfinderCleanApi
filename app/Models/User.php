<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'bio',
        'location',
        'birthdate',
        'website',
        'phone',
        'instagram',
        'twitter',
        'facebook',
        'private_profile',
        'allow_mentions',
        'email_notifications',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
            'last_active_at' => 'datetime',
            'private_profile' => 'boolean',
            'allow_mentions' => 'boolean',
            'email_notifications' => 'boolean',
        ];
    }

    /**
     * Obtiene los usuarios que siguen a este usuario.
     * Necesario para calcular 'followers_count'.
     */
    public function followers(): BelongsToMany
    {
        // 'following_id' es el ID del usuario actual (el que está siendo seguido)
        // 'follower_id' es el ID del usuario que sigue
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    /**
     * Obtiene los usuarios a los que este usuario sigue.
     * Necesario para calcular 'following_count'.
     */
    public function following(): BelongsToMany
    {
        // 'follower_id' es el ID del usuario actual (el que sigue)
        // 'following_id' es el ID del usuario que es seguido
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    /**
     * Obtiene los posts del usuario.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Obtiene las notificaciones del usuario.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Obtiene los check-ins realizados por esta persona.
     */
    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }
}
