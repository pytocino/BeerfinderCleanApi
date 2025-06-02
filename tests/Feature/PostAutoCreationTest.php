<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Beer;
use App\Models\Location;
use App\Models\Brewery;
use App\Services\AutoCreationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PostAutoCreationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $autoCreationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->autoCreationService = app(AutoCreationService::class);
        Storage::fake('public');
    }

    /** @test */
    public function can_create_post_with_new_beer_tag()
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->postJson('/api/v1/posts', [
            'content' => 'Probando una cerveza nueva',
            'tags' => [
                [
                    'type' => 'beer',
                    'name' => 'IPA Experimental'
                ]
            ]
        ]);

        $response->assertStatus(201);
        
        // Verificar que se creó la cerveza
        $this->assertDatabaseHas('beers', [
            'name' => 'IPA Experimental',
            'brewery_id' => null,
            'style_id' => null,
            'is_verified' => false
        ]);

        // Verificar que el post tiene la etiqueta correcta
        $post = $response->json('post');
        $this->assertNotEmpty($post['tags']);
        $this->assertEquals('beer', $post['tags'][0]['type']);
    }

    /** @test */
    public function can_create_post_with_new_location_tag()
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->postJson('/api/v1/posts', [
            'content' => 'En un lugar nuevo',
            'tags' => [
                [
                    'type' => 'location',
                    'name' => 'Bar Test',
                    'latitude' => -34.6037,
                    'longitude' => -58.3816,
                    'address' => 'Av. Test 123'
                ]
            ]
        ]);

        $response->assertStatus(201);

        // Verificar que se creó la ubicación
        $this->assertDatabaseHas('locations', [
            'name' => 'Bar Test',
            'latitude' => -34.6037,
            'longitude' => -58.3816,
            'address' => 'Av. Test 123',
            'verified' => false
        ]);

        // Verificar que el post tiene la etiqueta correcta
        $post = $response->json('post');
        $this->assertNotEmpty($post['tags']);
        $this->assertEquals('location', $post['tags'][0]['type']);
    }

    /** @test */
    public function can_create_post_with_existing_beer_tag()
    {
        $this->actingAs($this->user, 'sanctum');

        // Crear una cerveza existente
        $beer = Beer::factory()->create(['name' => 'Cerveza Existente']);

        $response = $this->postJson('/api/v1/posts', [
            'content' => 'Probando cerveza conocida',
            'tags' => [
                [
                    'type' => 'beer',
                    'id' => $beer->id
                ]
            ]
        ]);

        $response->assertStatus(201);

        // Verificar que no se creó una nueva cerveza
        $this->assertEquals(1, Beer::where('name', 'Cerveza Existente')->count());

        // Verificar que el post tiene la etiqueta correcta
        $post = $response->json('post');
        $this->assertNotEmpty($post['tags']);
        $this->assertEquals('beer', $post['tags'][0]['type']);
        $this->assertEquals($beer->id, $post['tags'][0]['id']);
    }

    /** @test */
    public function can_create_post_with_mixed_tags()
    {
        $this->actingAs($this->user, 'sanctum');

        // Crear un usuario existente para etiquetar
        $taggedUser = User::factory()->create();

        $response = $this->postJson('/api/v1/posts', [
            'content' => 'Post completo con varios tags',
            'tags' => [
                [
                    'type' => 'user',
                    'id' => $taggedUser->id
                ],
                [
                    'type' => 'beer',
                    'name' => 'Nueva Cerveza'
                ],
                [
                    'type' => 'location',
                    'name' => 'Nuevo Lugar',
                    'latitude' => -34.6037,
                    'longitude' => -58.3816
                ]
            ]
        ]);

        $response->assertStatus(201);

        // Verificar creaciones automáticas
        $this->assertDatabaseHas('beers', [
            'name' => 'Nueva Cerveza',
            'brewery_id' => null,
            'is_verified' => false
        ]);

        $this->assertDatabaseHas('locations', [
            'name' => 'Nuevo Lugar',
            'verified' => false
        ]);

        // Verificar que el post tiene todas las etiquetas
        $post = $response->json('post');
        $this->assertCount(3, $post['tags']);
    }

    /** @test */
    public function ignores_invalid_tags()
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->postJson('/api/v1/posts', [
            'content' => 'Post con tags inválidos',
            'tags' => [
                [
                    'type' => 'beer',
                    // Sin name ni id - debe ser ignorado
                ],
                [
                    'type' => 'location',
                    'name' => 'Lugar Válido'
                ],
                [
                    'type' => 'user',
                    'id' => 99999 // Usuario inexistente - debe ser ignorado
                ]
            ]
        ]);

        $response->assertStatus(201);

        // Solo debe procesarse el tag de ubicación válido
        $post = $response->json('post');
        $this->assertCount(1, $post['tags']);
        $this->assertEquals('location', $post['tags'][0]['type']);
    }

    /** @test */
    public function beer_without_brewery_creates_correctly()
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->postJson('/api/v1/posts', [
            'content' => 'Cerveza casera experimental',
            'tags' => [
                [
                    'type' => 'beer',
                    'name' => 'Homebrew Saison'
                ]
            ]
        ]);

        $response->assertStatus(201);

        // Verificar que se creó la cerveza sin brewery_id
        $this->assertDatabaseHas('beers', [
            'name' => 'Homebrew Saison',
            'brewery_id' => null,
            'style_id' => null,
            'is_verified' => false
        ]);
    }

    /** @test */
    public function validates_required_fields_for_tags()
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->postJson('/api/v1/posts', [
            'content' => 'Test de validación',
            'tags' => [
                [
                    // Sin type - debe fallar validación
                    'name' => 'Sin Tipo'
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tags.0.type');
    }
}
