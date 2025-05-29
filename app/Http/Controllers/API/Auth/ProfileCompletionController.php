<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Traits\HasUser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ProfileCompletionController extends Controller
{
    use HasUser;
    /**
     * Completa el perfil del usuario después del primer login
     */
    public function completeProfile(Request $request): JsonResponse
    {
        $user = $this->authenticatedUser();

        if ($user->profile_completed) {
            return response()->json([
                'message' => 'El perfil ya está completo',
                'user' => new UserResource($user)
            ], 200);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date|before:today',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:50',
            
            // Configuraciones de privacidad
            'private_profile' => 'sometimes|boolean',
            'allow_mentions' => 'sometimes|boolean',
            'show_online_status' => 'sometimes|boolean',
            'share_location' => 'sometimes|boolean',
            
            // Configuraciones de notificaciones generales
            'email_notifications' => 'sometimes|boolean',
            
            // Configuraciones de notificaciones específicas
            'notify_new_followers' => 'sometimes|boolean',
            'notify_likes' => 'sometimes|boolean',
            'notify_comments' => 'sometimes|boolean',
            'notify_mentions' => 'sometimes|boolean',
            'notify_following_posts' => 'sometimes|boolean',
            'notify_recommendations' => 'sometimes|boolean',
            'notify_trends' => 'sometimes|boolean',
            'notify_direct_messages' => 'sometimes|boolean',
            'notify_group_messages' => 'sometimes|boolean',
            'notify_events' => 'sometimes|boolean',
            'notify_updates' => 'sometimes|boolean',
            'notify_security' => 'sometimes|boolean',
            'notify_promotions' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos de validación incorrectos',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Actualizar campos del usuario si están presentes
        $userFields = ['username', 'private_profile'];
        $userUpdates = [];
        foreach ($userFields as $field) {
            if (isset($validated[$field])) {
                $userUpdates[$field] = $validated[$field];
            }
        }
        
        if (!empty($userUpdates)) {
            $user->update($userUpdates);
        }

        // Actualizar el perfil extendido
        $profileFields = [
            'bio', 'location', 'birthdate', 'website', 'phone',
            'instagram', 'twitter', 'facebook', 'timezone',
            // Configuraciones de privacidad
            'allow_mentions', 'show_online_status', 'share_location',
            // Configuraciones de notificaciones
            'email_notifications', 'notify_new_followers', 'notify_likes',
            'notify_comments', 'notify_mentions', 'notify_following_posts',
            'notify_recommendations', 'notify_trends', 'notify_direct_messages',
            'notify_group_messages', 'notify_events', 'notify_updates',
            'notify_security', 'notify_promotions'
        ];
        
        $profileUpdates = [];
        foreach ($profileFields as $field) {
            if (isset($validated[$field])) {
                $profileUpdates[$field] = $validated[$field];
            }
        }

        if (!empty($profileUpdates)) {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileUpdates
            );
        }

        // Marcar el perfil como completado
        $user->update(['profile_completed' => true]);

        return response()->json([
            'message' => 'Perfil completado exitosamente',
            'user' => new UserResource($user->fresh())
        ]);
    }

    /**
     * Verifica si el usuario necesita completar su perfil
     */
    public function checkProfileStatus(): JsonResponse
    {
        $user = $this->authenticatedUser();

        return response()->json([
            'profile_completed' => $user->profile_completed,
            'needs_onboarding' => !$user->profile_completed,
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Permite saltarse el proceso de completar perfil (opcional)
     */
    public function skipProfileCompletion(): JsonResponse
    {
        $user = $this->authenticatedUser();

        if ($user->profile_completed) {
            return response()->json([
                'message' => 'El perfil ya está completo'
            ], 200);
        }

        $user->update(['profile_completed' => true]);

        return response()->json([
            'message' => 'Proceso de onboarding omitido',
            'user' => new UserResource($user->fresh())
        ]);
    }
}
