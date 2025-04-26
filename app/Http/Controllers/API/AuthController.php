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

class AuthController extends Controller
{

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'confirmed',
                PasswordRule::min(8)
                    ->mixedCase()
                    ->numbers()
            ],
            'profile_picture' => 'nullable|string',
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'website' => 'nullable|string|max:255|url',
            'phone' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'private_profile' => 'nullable|boolean',
            'allow_mentions' => 'nullable|boolean',
            'email_notifications' => 'nullable|boolean',
        ]);

        // Preparamos los datos para la creación del usuario
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ];

        // Añadimos los campos opcionales si están presentes
        $optionalFields = [
            'username',
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
            'email_notifications'
        ];

        foreach ($optionalFields as $field) {
            if (isset($validated[$field])) {
                $userData[$field] = $validated[$field];
            }
        }

        $user = User::create($userData);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {

        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);


            // Verificar si el usuario existe antes de intentar autenticación
            $userExists = User::where('email', $request->email)->exists();

            if (!$userExists) {
                throw ValidationException::withMessages([
                    'email' => ['No existe una cuenta con este email.'],
                ]);
            }

            // Obtener usuario para verificar estado
            $user = User::where('email', $request->email)->first();

            // Verificar si la contraseña coincide
            $passwordValid = Hash::check($request->password, $user->password);

            if (!Auth::attempt($request->only('email', 'password'))) {

                throw ValidationException::withMessages([
                    'email' => ['Las credenciales proporcionadas son incorrectas.'],
                ]);
            }


            try {
                $token = $user->createToken('auth_token')->plainTextToken;
            } catch (\Exception $e) {
                throw $e;
            }


            return response()->json([
                'user' => new UserResource($user),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {

            throw $e;
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

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
                    ->letters()
                    ->symbols()
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

    public function updateProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:50|unique:users,username,' . $request->user()->id,
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $request->user()->id,
            'profile_picture' => 'nullable|string',
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'website' => 'nullable|string|max:255|url',
            'phone' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'private_profile' => 'nullable|boolean',
            'allow_mentions' => 'nullable|boolean',
            'email_notifications' => 'nullable|boolean',
        ]);

        $user = $request->user();
        $user->update($validated);

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Refresca el token de acceso del usuario actual.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshToken(Request $request): JsonResponse
    {
        try {
            // Obtenemos el usuario autenticado
            $user = $request->user();

            // Revocamos el token actual
            $request->user()->currentAccessToken()->delete();

            // Creamos un nuevo token
            $token = $user->createToken('auth_token')->plainTextToken;


            return response()->json([
                'user' => new UserResource($user),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {


            return response()->json([
                'message' => 'No se pudo refrescar el token',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
