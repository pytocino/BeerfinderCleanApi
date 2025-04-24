<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    // Variables globales para tests
    protected $defaultEmail = 'test@example.com';
    protected $defaultPassword = 'Password123!';
    protected $defaultUsername = 'testuser';
    protected $defaultName = 'Test User';

    // Datos para registro y actualización de perfil
    protected function getUserData($overrides = [])
    {
        $default = [
            'name' => $this->defaultName,
            'username' => $this->defaultUsername,
            'email' => $this->defaultEmail,
            'password' => $this->defaultPassword,
            'password_confirmation' => $this->defaultPassword,
            'bio' => 'Test bio',
            'location' => 'Test City',
            'website' => 'https://example.com',
            'instagram' => 'testaccount',
            'private_profile' => false
        ];

        return array_merge($default, $overrides);
    }

    /**
     * @param array $overrides
     * @return \App\Models\User
     */
    protected function createTestUser($overrides = [])
    {
        // Valores predeterminados incluyendo campos de fecha
        $userData = array_merge([
            'name' => $this->defaultName,
            'username' => $this->defaultUsername,
            'email' => $this->defaultEmail,
            'password' => Hash::make($this->defaultPassword),
            'birthdate' => now()->subYears(25), // Fecha de nacimiento hace 25 años
            'email_verified_at' => now(),       // Email verificado
            'last_active_at' => now(),          // Última actividad
            'bio' => 'Test bio',
            'location' => 'Test City'
        ], $overrides);

        return User::factory()->create($userData);
    }

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/v1/auth/register', $this->getUserData());

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user',
                'access_token',
                'token_type'
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $this->defaultName,
            'email' => $this->defaultEmail,
            'bio' => 'Test bio',
            'location' => 'Test City'
        ]);
    }

    public function test_user_can_login()
    {
        $this->createTestUser();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->defaultEmail,
            'password' => $this->defaultPassword
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user',
                'access_token',
                'token_type'
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $this->createTestUser();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->defaultEmail,
            'password' => 'WrongPassword123!'
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_logout()
    {
        $user = $this->createTestUser();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Sesión cerrada correctamente']);
    }

    public function test_user_can_request_password_reset()
    {
        Notification::fake();

        $user = $this->createTestUser();

        $response = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => $this->defaultEmail
        ]);

        $response->assertStatus(200);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_user_can_reset_password()
    {
        $user = $this->createTestUser();

        // Generar token de reseteo
        $token = Password::createToken($user);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'token' => $token,
            'email' => $this->defaultEmail,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!'
        ]);

        $response->assertStatus(200);

        // Verificar que la contraseña se actualizó
        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
    }

    public function test_user_can_change_password()
    {
        $user = $this->createTestUser([
            'password' => Hash::make('CurrentPass123!')
        ]);

        $response = $this->actingAs($user)
            ->putJson('/api/v1/auth/change-password', [
                'current_password' => 'CurrentPass123!',
                'password' => 'NewPassword456!',
                'password_confirmation' => 'NewPassword456!'
            ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Contraseña cambiada correctamente']);

        // Verificar que la contraseña se actualizó
        $this->assertTrue(Hash::check('NewPassword456!', $user->fresh()->password));
    }

    public function test_user_cannot_change_password_with_incorrect_current_password()
    {
        $user = $this->createTestUser([
            'password' => Hash::make('CurrentPass123!')
        ]);

        $response = $this->actingAs($user)
            ->putJson('/api/v1/auth/change-password', [
                'current_password' => 'WrongPassword!',
                'password' => 'NewPassword456!',
                'password_confirmation' => 'NewPassword456!'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['current_password']);
    }

    public function test_user_can_update_profile()
    {
        $user = $this->createTestUser([
            'name' => 'Usuario Original',
            'email' => 'original@example.com',
        ]);

        $updatedData = $this->getUserData([
            'name' => 'Nuevo Nombre',
            'bio' => 'Esta es mi nueva biografía',
            'location' => 'Madrid, España',
            'website' => 'https://miwebsite.com',
            'instagram' => 'minuevousuario',
            'private_profile' => true
        ]);

        $response = $this->actingAs($user)
            ->putJson('/api/v1/auth/update-profile', $updatedData);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Perfil actualizado correctamente')
            ->assertJsonPath('user.name', 'Nuevo Nombre');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nuevo Nombre',
            'bio' => 'Esta es mi nueva biografía',
            'location' => 'Madrid, España',
            'website' => 'https://miwebsite.com',
            'instagram' => 'minuevousuario',
            'private_profile' => true
        ]);
    }

    public function test_unauthorized_user_cannot_update_profile()
    {
        $response = $this->putJson('/api/v1/auth/update-profile', ['name' => 'Test']);

        $response->assertStatus(401);
    }

    public function test_username_must_be_unique_when_updating_profile()
    {
        $user1 = $this->createTestUser(['username' => 'usuario1']);
        $user2 = $this->createTestUser([
            'username' => 'usuario2',
            'email' => 'test2@example.com' // Email único para el segundo usuario
        ]);

        $response = $this->actingAs($user2)
            ->putJson('/api/v1/auth/update-profile', [
                'username' => 'usuario1'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    public function test_user_can_refresh_token()
    {
        // Crear usuario de prueba
        $user = $this->createTestUser();

        // Crear un token real en la base de datos
        $initialToken = $user->createToken('auth_token')->plainTextToken;

        // Verificar que hay exactamente un token antes de refrescar
        $this->assertEquals(1, $user->tokens()->count());

        // Realizar la solicitud al endpoint de refresh token usando el token inicial
        $response = $this->withHeader('Authorization', 'Bearer ' . $initialToken)
            ->postJson('/api/v1/auth/refresh-token');

        // Verificar respuesta exitosa
        $response->assertStatus(200)
            ->assertJsonStructure([
                'user',
                'access_token',
                'token_type'
            ]);

        // Recargar el usuario para obtener datos actualizados
        // Forzamos la carga desde la base de datos
        $user = User::find($user->id);

        // Verificar que sigue habiendo un solo token (se eliminó uno y se creó uno nuevo)
        $tokenCount = $user->tokens()->count();
        $this->assertEquals(1, $tokenCount, "Se esperaba 1 token, pero se encontraron {$tokenCount}");

        // Verificar que el token original ya no funciona
        $originalTokenResponse = $this->withHeader('Authorization', 'Bearer ' . $initialToken)
            ->getJson('/api/v1/users/me');
        $originalTokenResponse->assertStatus(401);

        // Verificar que el nuevo token funciona para acceder a rutas protegidas
        $newToken = $response->json('access_token');
        $newTokenResponse = $this->withHeader('Authorization', 'Bearer ' . $newToken)
            ->getJson('/api/v1/users/me');
        $newTokenResponse->assertStatus(200);
    }
}
