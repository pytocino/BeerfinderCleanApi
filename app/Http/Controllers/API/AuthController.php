<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

/**
 * @group Autenticación
 *
 * APIs para gestionar la autenticación de usuarios
 */
class AuthController extends Controller
{
    /**
     * Registrar un nuevo usuario
     *
     * Crea una nueva cuenta de usuario en el sistema.
     *
     * @bodyParam name string required El nombre del usuario. Example: Juan Pérez
     * @bodyParam email string required Email del usuario. Example: juan@example.com
     * @bodyParam password string required Contraseña (mínimo 8 caracteres). Example: secreto123
     * @bodyParam password_confirmation string required Confirmación de la contraseña. Example: secreto123
     *
     * @response 201 {
     *  "user": {
     *      "id": 1,
     *      "name": "Juan Pérez",
     *      "email": "juan@example.com"
     *  },
     *  "access_token": "1|laravel_sanctum_token",
     *  "token_type": "Bearer"
     * }
     *
     * @response 422 {
     *  "message": "The email has already been taken.",
     *  "errors": {
     *    "email": ["Este correo ya está registrado."]
     *  }
     * }
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'confirmed',
                PasswordRule::min(8)
                    ->mixedCase()
                    ->numbers()
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Iniciar sesión
     *
     * Autentica al usuario y devuelve un token de acceso.
     *
     * @bodyParam email string required Email del usuario. Example: juan@example.com
     * @bodyParam password string required Contraseña del usuario. Example: secreto123
     *
     * @response {
     *  "user": {
     *      "id": 1,
     *      "name": "Juan Pérez",
     *      "email": "juan@example.com"
     *  },
     *  "access_token": "1|laravel_sanctum_token",
     *  "token_type": "Bearer"
     * }
     *
     * @response 422 {
     *  "message": "Las credenciales proporcionadas son incorrectas."
     * }
     */
    public function login(Request $request): JsonResponse
    {
        Log::info('Intento de login iniciado', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            Log::info('Validación de datos exitosa');

            // Verificar si el usuario existe antes de intentar autenticación
            $userExists = User::where('email', $request->email)->exists();
            Log::info('Verificación de existencia de usuario', ['exists' => $userExists]);

            if (!$userExists) {
                Log::warning('Intento de login con email inexistente', ['email' => $request->email]);
                throw ValidationException::withMessages([
                    'email' => ['No existe una cuenta con este email.'],
                ]);
            }

            // Obtener usuario para verificar estado
            $user = User::where('email', $request->email)->first();
            Log::info('Usuario encontrado en la base de datos', ['user_id' => $user->id]);

            // Verificar si la contraseña coincide
            $passwordValid = Hash::check($request->password, $user->password);
            Log::info('Verificación de contraseña', ['es_valida' => $passwordValid]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                Log::warning('Fallo de autenticación - Auth::attempt falló', [
                    'email' => $request->email
                ]);

                throw ValidationException::withMessages([
                    'email' => ['Las credenciales proporcionadas son incorrectas.'],
                ]);
            }

            Log::info('Usuario autenticado correctamente', ['user_id' => $user->id]);

            try {
                $token = $user->createToken('auth_token')->plainTextToken;
                Log::info('Token generado correctamente');
            } catch (\Exception $e) {
                Log::error('Error al generar token', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            Log::info('Login exitoso', ['user_id' => $user->id]);

            return response()->json([
                'user' => new UserResource($user),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (ValidationException $e) {
            Log::warning('Error de validación en login', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error inesperado en login', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Cerrar sesión
     *
     * Revoca el token de acceso actual del usuario.
     *
     * @authenticated
     *
     * @response {
     *  "message": "Sesión cerrada correctamente"
     * }
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    /**
     * Obtener usuario autenticado
     *
     * Devuelve la información del usuario actualmente autenticado.
     *
     * @authenticated
     *
     * @response {
     *  "id": 1,
     *  "name": "Juan Pérez",
     *  "email": "juan@example.com",
     *  "bio": "Amante de las cervezas artesanales",
     *  "location": "Madrid, España",
     *  "profile_picture": "https://example.com/avatars/juan.jpg",
     *  "created_at": "2023-01-01T00:00:00.000000Z"
     * }
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json(new UserResource($request->user()->load([
            'followers',
            'following',
            'checkIns',
            'favorites'
        ])));
    }

    /**
     * Solicitar restablecimiento de contraseña
     *
     * Envía un correo con un enlace para restablecer la contraseña.
     *
     * @bodyParam email string required Email del usuario. Example: juan@example.com
     *
     * @response {
     *  "message": "Se ha enviado un enlace para restablecer la contraseña"
     * }
     *
     * @response 422 {
     *  "message": "No podemos encontrar un usuario con ese correo electrónico."
     * }
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)]);
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    /**
     * Restablecer contraseña
     *
     * Restablece la contraseña del usuario usando el token recibido por email.
     *
     * @bodyParam token string required El token de reset recibido por email. Example: abcdef123456
     * @bodyParam email string required Email del usuario. Example: juan@example.com
     * @bodyParam password string required Nueva contraseña (mínimo 8 caracteres). Example: nuevaContraseña123
     * @bodyParam password_confirmation string required Confirmación de la nueva contraseña. Example: nuevaContraseña123
     *
     * @response {
     *  "message": "La contraseña ha sido restablecida correctamente"
     * }
     *
     * @response 422 {
     *  "message": "El token es inválido."
     * }
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)
                    ->mixedCase()
                    ->numbers()
            ],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)]);
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    /**
     * Cambiar contraseña
     *
     * Permite al usuario autenticado cambiar su contraseña.
     *
     * @authenticated
     *
     * @bodyParam current_password string required La contraseña actual. Example: contraseñaActual123
     * @bodyParam password string required Nueva contraseña (mínimo 8 caracteres). Example: nuevaContraseña123
     * @bodyParam password_confirmation string required Confirmación de la nueva contraseña. Example: nuevaContraseña123
     *
     * @response {
     *  "message": "Contraseña cambiada correctamente"
     * }
     *
     * @response 422 {
     *  "message": "La contraseña actual es incorrecta."
     * }
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => [
                'required',
                'string',
                'confirmed',
                PasswordRule::min(8)
                    ->mixedCase()
                    ->numbers()
            ],
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['La contraseña actual es incorrecta.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Contraseña cambiada correctamente',
        ]);
    }
}
