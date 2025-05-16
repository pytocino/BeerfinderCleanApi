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
        // This part is generally fine, it redirects the user to Google's OAuth screen.
        // The client (frontend) will initiate this.
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $username = $this->generateUniqueUsername($googleUser->getName(), $googleUser->getEmail());
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'username' => $username,
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(),
                ]);
            }

            $token = $user->createToken('google-auth-token')->plainTextToken;

            // Cambia esto por tu esquema personalizado
            $appScheme = env('APP_FRONTEND_URL_SCHEME', 'mybeerapp');
            $successRedirectUrl = "{$appScheme}://auth/google/callback?status=success&token={$token}";

            return redirect()->away($successRedirectUrl);
        } catch (\Exception $e) {
            Log::error('Google OAuth callback error: ' . $e->getMessage(), ['exception' => $e]);
            $appScheme = env('APP_FRONTEND_URL_SCHEME', 'mybeerapp');
            $errorRedirectUrl = "{$appScheme}://auth/google/callback?status=error&message=" . urlencode($e->getMessage());
            return redirect()->away($errorRedirectUrl);
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
