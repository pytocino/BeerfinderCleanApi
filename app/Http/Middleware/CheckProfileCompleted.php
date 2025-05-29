<?php

namespace App\Http\Middleware;

use App\Traits\HasUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileCompleted
{
    use HasUser;
    /**
     * Handle an incoming request.
     *
     * Middleware opcional para verificar si el usuario necesita completar su perfil
     * Solo aplica para rutas específicas donde sea necesario tener perfil completo
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $this->authenticatedUser();

        if ($user && !$user->profile_completed) {
            return response()->json([
                'message' => 'Necesitas completar tu perfil para acceder a esta funcionalidad',
                'needs_onboarding' => true,
                'redirect_to' => '/onboarding'
            ], 403);
        }

        return $next($request);
    }
}
