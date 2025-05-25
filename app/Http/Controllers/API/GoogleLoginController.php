<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        // Capturar el redirect_uri para devolverlo en el callback
        $redirectUri = $request->input('redirect_uri');
        Log::info('Inicio de autenticación Google con redirect_uri: ' . ($redirectUri ?? 'no proporcionado'));
        
        // Guardar el redirect_uri en la sesión para recuperarlo en el callback
        session(['google_redirect_uri' => $redirectUri]);
        
        // Para aplicaciones móviles, redirigimos a Google sin parámetros adicionales
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
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
            $redirectUriParam = $request->input('redirect_uri');
            if ($redirectUriParam) {
                $successRedirectUrl = "{$redirectUriParam}?status=success&token={$token}";
            } else {
                $appScheme = env('APP_FRONTEND_URL_SCHEME', 'beerfinder');
                $successRedirectUrl = "{$appScheme}://auth/google/callback?status=success&token={$token}";
            }

            return redirect()->away($successRedirectUrl);
        } catch (\Exception $e) {
            Log::error('Google OAuth callback error: ' . $e->getMessage(), ['exception' => $e]);
            $redirectUriParam = $request->input('redirect_uri');
            if ($redirectUriParam) {
                $errorRedirectUrl = "{$redirectUriParam}?status=error&message=" . urlencode($e->getMessage());
            } else {
                $appScheme = env('APP_FRONTEND_URL_SCHEME', 'beerfinder');
                $errorRedirectUrl = "{$appScheme}://auth/google/callback?status=error&message=" . urlencode($e->getMessage());
            }
            return redirect()->away($errorRedirectUrl);
        }
    }

    /**
     * Maneja la autenticación directamente desde el cliente móvil
     * Recibe el authorization code o token de ID de Google y lo procesa
     */
    public function handleMobileAuth(Request $request)
    {
        try {
            // Validar entrada - acepta tanto authorization_code como id_token
            $authCode = $request->input('authorization_code');
            $idToken = $request->input('id_token');
            
            if (!$authCode && !$idToken) {
                Log::error('Ni authorization code ni token de ID proporcionados');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Se requiere authorization_code o id_token'
                ], 400);
            }
            
            // Priorizar authorization_code si está presente
            if ($authCode) {
                Log::info('Procesando autenticación móvil con authorization code');
                $googleUser = $this->exchangeCodeForUserInfo($authCode);
            } else {
                Log::info('Procesando autenticación móvil con token ID');
                $googleUser = $this->verifyGoogleIdToken($idToken);
            }
            
            if (!$googleUser) {
                Log::error('Token de Google inválido o verificación fallida');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token de Google inválido'
                ], 401);
            }
            
            $email = $googleUser['email'];
            $name = $googleUser['name'] ?? null;
            
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
            Log::error('Error en autenticación móvil: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error interno del servidor'
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

    /**
     * Intercambia un authorization code por información del usuario de Google
     */
    private function exchangeCodeForUserInfo(string $authCode): ?array
    {
        try {
            Log::info('Intercambiando authorization code: ' . substr($authCode, 0, 20) . '...');
            
            // Intercambiar el authorization code por un access token
            $response = Http::post('https://oauth2.googleapis.com/token', [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'code' => $authCode,
                'grant_type' => 'authorization_code',
                'redirect_uri' => config('services.google.redirect'),
            ]);

            if (!$response->successful()) {
                Log::error('Error intercambiando authorization code: ' . $response->body());
                return null;
            }

            $tokenData = $response->json();
            $accessToken = $tokenData['access_token'] ?? null;

            if (!$accessToken) {
                Log::error('No se obtuvo access token del intercambio');
                return null;
            }

            // Usar el access token para obtener información del usuario
            $userResponse = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v2/userinfo');

            if (!$userResponse->successful()) {
                Log::error('Error obteniendo información del usuario: ' . $userResponse->body());
                return null;
            }

            $userData = $userResponse->json();
            return [
                'email' => $userData['email'] ?? null,
                'name' => $userData['name'] ?? null,
            ];

        } catch (\Exception $e) {
            Log::error('Excepción en exchangeCodeForUserInfo: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verifica un token de ID de Google
     */
    private function verifyGoogleIdToken(string $idToken): ?array
    {
        try {
            Log::info('Verificando Google ID token: ' . substr($idToken, 0, 20) . '...');
            
            // Verificar el token de ID con Google
            $response = Http::get('https://oauth2.googleapis.com/tokeninfo', [
                'id_token' => $idToken
            ]);

            if (!$response->successful()) {
                Log::error('Error verificando ID token: ' . $response->body());
                return null;
            }

            $tokenInfo = $response->json();
            
            // Verificar que el token es para nuestra aplicación
            $expectedClientId = config('services.google.client_id');
            if ($tokenInfo['aud'] !== $expectedClientId) {
                Log::error('ID token no pertenece a nuestra aplicación');
                return null;
            }

            return [
                'email' => $tokenInfo['email'] ?? null,
                'name' => $tokenInfo['name'] ?? null,
            ];

        } catch (\Exception $e) {
            Log::error('Excepción en verifyGoogleIdToken: ' . $e->getMessage());
            return null;
        }
    }
}
