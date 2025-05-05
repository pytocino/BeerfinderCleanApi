<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait HasUser
{
    /**
     * Relación con el usuario propietario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener el usuario autenticado actual.
     */
    public function authenticatedUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Verifica si el modelo pertenece al usuario autenticado.
     */
    public function belongsToAuthenticatedUser(): bool
    {
        $user = $this->authenticatedUser();
        return $user && $this->user_id === $user->id;
    }
}
