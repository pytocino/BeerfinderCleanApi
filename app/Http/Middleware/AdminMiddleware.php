<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\HasUser;

class AdminMiddleware
{
    use HasUser;

    /**
     * Verifica que el usuario autenticado sea un administrador.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $this->authenticatedUser();

        if (!$user || !$user->is_admin) {
            return response()->json(['message' => 'Acceso no autorizado.'], 403);
        }

        return $next($request);
    }
}
