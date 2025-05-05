<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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
        ]);

        // Crear el usuario con campos básicos
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'profile_picture' => $validated['profile_picture'] ?? null,
            'private_profile' => false,
        ]);

        // Crear perfil vacío para el usuario
        $user->profile()->create([
            'user_id' => $user->id,
            'private_profile' => false,
            'allow_mentions' => true,
            'email_notifications' => true,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Verificar si el usuario existe antes de intentar autenticación
        $user = User::where('email', '=', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['No existe una cuenta con este email.'],
            ]);
        }

        // Verificar si la contraseña coincide
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Intentar autenticar al usuario
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Generar token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();
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

    public function updateUserProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
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

        // Obtenemos el usuario autenticado
        $user = auth()->user();
        // Actualizamos el perfil del usuario
        $user->profile()->update([
            'bio' => $validated['bio'],
            'location' => $validated['location'],
            'birthdate' => $validated['birthdate'],
            'website' => $validated['website'],
            'phone' => $validated['phone'],
            'instagram' => $validated['instagram'],
            'twitter' => $validated['twitter'],
            'facebook' => $validated['facebook'],
            'private_profile' => $validated['private_profile'] ?? false,
            'allow_mentions' => $validated['allow_mentions'] ?? true,
            'email_notifications' => $validated['email_notifications'] ?? true,
        ]);

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'userProfile' => new UserProfileResource($user),
        ]);
    }

    public function refreshToken(Request $request): JsonResponse
    {
        try {
            // Obtenemos el usuario autenticado
            $user = auth()->user();

            // Revocamos el token actual
            auth()->user()->currentAccessToken()->delete();

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

    public function me(): JsonResponse
    {
        $user = auth()->user()->load('profile')->loadCount([
            'followers' => function ($query) {
                $query->where('follows.status', '=', 'accepted');
            },
            'following' => function ($query) {
                $query->where('follows.status', '=', 'accepted');
            },
            'posts'
        ]);

        return response()->json(new UserResource($user));
    }

    public function getMyStats(Request $request): JsonResponse
    {
        $user = auth()->user();

        // Número de cervezas distintas tomadas (por posts)
        $distinctBeers = $user->posts()->distinct('beer_id')->count('beer_id');

        // Número de lugares distintos visitados (por posts)
        $distinctLocations = $user->posts()->whereNotNull('location_id')->distinct('location_id')->count('location_id');

        // Número de estilos de cerveza distintos probados
        $distinctStyles = $user->posts()
            ->join('beers', 'posts.beer_id', '=', 'beers.id')
            ->distinct('beers.style_id')
            ->count('beers.style_id');

        return response()->json([
            'distinct_beers'     => $distinctBeers,
            'distinct_locations' => $distinctLocations,
            'distinct_styles'    => $distinctStyles,
        ]);
    }

    public function getMyPosts(Request $request): JsonResponse
    {
        $user = auth()->user();
        $posts = $user->posts()
            ->with(['user', 'beer', 'location'])
            ->latest()
            ->paginate(15);

        return response()->json(PostResource::collection($posts));
    }

    public function getMyFriendsPosts(Request $request): JsonResponse
    {
        $user = auth()->user();

        // Obtener los IDs de usuarios que sigue (amigos) con estado 'accepted'
        $followingIds = $user->following()
            ->wherePivot('status', '=', 'accepted')
            ->pluck('users.id');

        // Obtener los posts de esos usuarios
        $friendsPosts = Post::whereIn('user_id', $followingIds)
            ->with(['user', 'beer', 'location', 'comments', 'likes'])
            ->latest()
            ->paginate(15);

        logger($friendsPosts);
        return response()->json(PostResource::collection($friendsPosts));
    }
}
