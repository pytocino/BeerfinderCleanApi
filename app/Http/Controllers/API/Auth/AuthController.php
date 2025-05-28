<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Traits\HasUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use HasUser;

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

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'profile_picture' => $validated['profile_picture'] ?? null,
            'private_profile' => false,
        ]);

        // Crear perfil extendido usando UserProfile y fillable definidos
        $profileData = [
            'user_id' => $user->id,
            'bio' => null,
            'location' => null,
            'birthdate' => null,
            'website' => null,
            'phone' => null,
            'instagram' => null,
            'twitter' => null,
            'facebook' => null,
            'allow_mentions' => true,
            'email_notifications' => true,
            'timezone' => null,
        ];
        $user->profile()->create($profileData);

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

        $user = User::where('email', '=', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['No existe una cuenta con este email.'],
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $this->authenticatedUser();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
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
            'allow_mentions' => 'nullable|boolean',
            'email_notifications' => 'nullable|boolean',
            'timezone' => 'nullable|string|max:255',
        ]);

        $user = $this->authenticatedUser();
        $profile = $user->profile;

        // Solo actualiza los campos definidos en fillable de UserProfile
        $profile->update($validated);

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'userProfile' => new UserProfileResource($user),
        ]);
    }

    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $user = $this->authenticatedUser();
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
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
        $user = $this->authenticatedUser()->load('profile')->loadCount([
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
        $user = $this->authenticatedUser();

        $distinctBeers = $user->posts()->distinct('beer_id')->count('beer_id');
        $distinctLocations = $user->posts()->whereNotNull('location_id')->distinct('location_id')->count('location_id');
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
        $user = $this->authenticatedUser();
        $posts = $user->posts()
            ->with([
                'user', 
                'beer', 
                'location',
                'beerReview:id,post_id,rating,review_text',
                'beerReview.beer:id,name',
                'beerReview.location:id,name'
            ])
            ->latest()
            ->paginate(15);

        return response()->json(PostResource::collection($posts));
    }

    public function getMyFriendsPosts(Request $request): JsonResponse
    {
        $user = $this->authenticatedUser();

        $followingIds = $user->following()
            ->wherePivot('status', '=', 'accepted')
            ->pluck('users.id');

        $friendsPosts = Post::whereIn('user_id', $followingIds)
            ->with([
                'user', 
                'beer', 
                'location', 
                'comments', 
                'likes',
                'beerReview:id,post_id,rating,review_text',
                'beerReview.beer:id,name',
                'beerReview.location:id,name'
            ])
            ->latest()
            ->paginate(15);

        logger($friendsPosts);
        return response()->json(PostResource::collection($friendsPosts));
    }
}
