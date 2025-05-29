<?php

namespace App\Models;

use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasUser;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'profile_picture',
        'is_admin',
        'last_active_at',
        'private_profile',
        'profile_completed',
        'status',
    ];

    /**
     * Los atributos que deben ocultarse en arrays/JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_active_at' => 'datetime',
        'is_admin' => 'boolean',
        'private_profile' => 'boolean',
        'profile_completed' => 'boolean',
    ];

    /**
     * Relación con el perfil extendido del usuario.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Relación con los posts creados por el usuario.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Relación con las reseñas de cervezas del usuario.
     */
    public function beerReviews(): HasMany
    {
        return $this->hasMany(BeerReview::class);
    }

    /**
     * Usuarios que siguen a este usuario.
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follows', 'following_id', 'follower_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Usuarios a los que sigue este usuario.
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follows', 'follower_id', 'following_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Relación con los comentarios realizados por el usuario.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relación con los likes dados por el usuario.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Relación con las conversaciones en las que participa el usuario.
     */
    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
            ->withPivot('last_read_at', 'is_muted', 'role')
            ->withTimestamps();
    }

    /**
     * Mensajes enviados por el usuario.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function favoritedBeers(): BelongsToMany
    {
        return $this->belongsToMany(
            Beer::class,
            'favorite_beers',
            'user_id',
            'favorable_id'
        )
            ->wherePivot('favorable_type', Beer::class)
            ->withTimestamps();
    }

    /**
     * Reportes enviados por el usuario.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Reportes revisados por el usuario (para admins).
     */
    public function reviewedReports(): HasMany
    {
        return $this->hasMany(Report::class, 'reviewed_by');
    }

    /**
     * Transacciones realizadas por el usuario.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Obtener solo seguidores aceptados.
     */
    public function acceptedFollowers()
    {
        return $this->followers()->wherePivot('status', 'accepted');
    }

    /**
     * Obtener solo seguidos aceptados.
     */
    public function acceptedFollowing()
    {
        return $this->following()->wherePivot('status', 'accepted');
    }

    /**
     * Verificar si el usuario es administrador.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Verificar si el usuario tiene perfil privado.
     */
    public function hasPrivateProfile(): bool
    {
        return $this->private_profile;
    }

    /**
     * Actualizar timestamp de última actividad.
     */
    public function updateLastActive(): void
    {
        $this->update(['last_active_at' => now()]);
    }

    /**
     * IDs únicos de cervezas reseñadas por el usuario.
     */
    public function reviewedBeerIds(): array
    {
        return $this->beerReviews()->pluck('beer_id')->unique()->toArray();
    }

    /**
     * IDs únicos de estilos de cerveza reseñados por el usuario.
     */
    public function reviewedStyleIds(): array
    {
        return Beer::whereIn('id', $this->reviewedBeerIds())->pluck('style_id')->unique()->toArray();
    }

    /**
     * IDs únicos de cervecerías reseñadas por el usuario.
     */
    public function reviewedBreweryIds(): array
    {
        return Beer::whereIn('id', $this->reviewedBeerIds())->pluck('brewery_id')->unique()->toArray();
    }

    /**
     * IDs únicos de ubicaciones donde ha hecho reviews.
     */
    public function reviewedLocationIds(): array
    {
        return $this->beerReviews()->whereNotNull('location_id')->pluck('location_id')->unique()->toArray();
    }

    /**
     * Países de origen de los estilos de cerveza reseñados.
     */
    public function reviewedStyleCountries(): array
    {
        return BeerStyle::whereIn('id', $this->reviewedStyleIds())->pluck('origin_country')->unique()->toArray();
    }
}
