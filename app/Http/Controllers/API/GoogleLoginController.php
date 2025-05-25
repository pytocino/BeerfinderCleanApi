<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource; // Import UserResource
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;     // Import Hash
use Illuminate\Support\Facades\Log;       // Import Log for error handling
use Illuminate\Support\Str;               // Import Str for string manipulations

class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        // Capturar el redirect_uri para devolverlo en el callback
        $redirectUri = request()->input('redirect_uri');
        Log::info('Inicio de autenticación Google con redirect_uri: ' . ($redirectUri ?? 'no proporcionado'));
        
        // Guardar el redirect_uri en la sesión para recuperarlo en el callback
        session(['google_redirect_uri' => $redirectUri]);
        
        // Para aplicaciones móviles, redirigimos a Google sin parámetros adicionales
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback()
    {
        try {
            // Recuperamos el redirect_uri de la sesión que almacenamos previamente
            $redirectUri = session('google_redirect_uri');
            Log::info('Manejando callback de Google. Redirect URI: ' . ($redirectUri ?? 'no proporcionado'));
            
            $googleUser = Socialite::driver('google')->user();
            Log::info('Usuario Google obtenido: ' . $googleUser->getEmail());

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $username = $this->generateUniqueUsername($googleUser->getName(), $googleUser->getEmail());
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'username' => $username,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);
            }

            $token = $user->createToken('google-auth-token')->plainTextToken;

            // Usar el redirect_uri recibido si existe, si no el esquema por defecto
            $redirectUri = request()->input('redirect_uri');
            if ($redirectUri) {
                $successRedirectUrl = "{$redirectUri}?status=success&token={$token}";
            } else {
                $appScheme = env('APP_FRONTEND_URL_SCHEME', 'beerfinder');
                $successRedirectUrl = "{$appScheme}://auth/google/callback?status=success&token={$token}";
            }

            return redirect()->away($successRedirectUrl);
        } catch (\Exception $e) {
            Log::error('Google OAuth callback error: ' . $e->getMessage(), ['exception' => $e]);
            $redirectUri = request()->input('redirect_uri');
            if ($redirectUri) {
                $errorRedirectUrl = "{$redirectUri}?status=error&message=" . urlencode($e->getMessage());
            } else {
                $appScheme = env('APP_FRONTEND_URL_SCHEME', 'beerfinder');
                $errorRedirectUrl = "{$appScheme}://auth/google/callback?status=error&message=" . urlencode($e->getMessage());
            }
            return redirect()->away($errorRedirectUrl);
        }
    }

    /**
     * Maneja la autenticación directamente desde el cliente móvil
     * Recibe el token de ID directamente de Google y lo verifica
     */
    public function handleMobileAuth()
    {
        try {
            $idToken = request()->input('id_token');
            
            if (!$idToken) {
                Log::error('Token de ID no proporcionado');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token de ID no proporcionado'
                ], 400);
            }
            
            Log::info('Procesando autenticación móvil con token ID');
            
            // Aquí deberíamos verificar el token con la API de Google
            // Por ahora, asumiremos que el token es válido y contiene la información necesaria
            
            // Esta es una implementación simulada. En producción, deberías verificar el token con Google
            $payload = json_decode(base64_decode(explode('.', $idToken)[1]), true);
            
            if (!isset($payload['email'])) {
                Log::error('Token inválido - email no encontrado');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token inválido'
                ], 400);
            }
            
            $email = $payload['email'];
            $name = $payload['name'] ?? null;
            
            Log::info("Autenticando usuario con email: $email");
            
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $username = $this->generateUniqueUsername($name, $email);
                Log::info("Creando nuevo usuario con username: $username");
                
                $user = User::create([
                    'name' => $name ?? explode('@', $email)[0],
                    'email' => $email,
                    'username' => $username,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);
            }
            
            $token = $user->createToken('google-mobile-auth-token')->plainTextToken;
            
            return response()->json([
                'status' => 'success',
                'message' => 'Autenticación exitosa',
                'token' => $token,
                'user' => new UserResource($user)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en autenticación móvil: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error en autenticación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generates a unique username.
     */
    private function generateUniqueUsername(?string $name, string $email): string
    {
        $baseUsername = null;
        if (!empty($name)) {
            $baseUsername = Str::slug($name);
        }

        if (empty($baseUsername)) {
            $baseUsername = Str::slug(explode('@', $email)[0]);
        }

        // Fallback if still empty (e.g., name was just symbols)
        if (empty($baseUsername)) {
            $baseUsername = 'user';
        }

        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        return $username;
    }
}
