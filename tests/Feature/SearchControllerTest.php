<?php

namespace Tests\Feature;

use App\Models\Beer;
use App\Models\Brewery;
use App\Models\BeerStyle;
use App\Models\Location;
use App\Models\User;
use App\Models\CheckIn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $beers = [];
    protected $breweries = [];
    protected $styles = [];
    protected $locations = [];
    protected $users = [];

    /**
     * Configurar el entorno de prueba.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Crear un usuario para autenticación
        $this->user = User::factory()->create();

        // Crear estilos de cerveza
        $this->styles = [
            'IPA' => BeerStyle::factory()->create([
                'name' => 'IPA',
                'description' => 'Indian Pale Ale, cerveza amarga con mucho lúpulo'
            ]),
            'Lager' => BeerStyle::factory()->create([
                'name' => 'Lager',
                'description' => 'Cerveza ligera y refrescante'
            ]),
            'Stout' => BeerStyle::factory()->create([
                'name' => 'Stout',
                'description' => 'Cerveza negra y cremosa'
            ])
        ];

        // Crear cervecerías
        $this->breweries = [
            'Mahou' => Brewery::factory()->create([
                'name' => 'Cervecería Mahou',
                'country' => 'España',
                'city' => 'Madrid'
            ]),
            'Estrella' => Brewery::factory()->create([
                'name' => 'Estrella Galicia',
                'country' => 'España',
                'city' => 'A Coruña'
            ]),
            'Brooklyn' => Brewery::factory()->create([
                'name' => 'Brooklyn Brewery',
                'country' => 'Estados Unidos',
                'city' => 'Nueva York'
            ])
        ];

        // Crear cervezas
        $this->beers = [
            'MahouClasica' => Beer::factory()->create([
                'name' => 'Mahou Clásica',
                'brewery_id' => $this->breweries['Mahou']->id,
                'style_id' => $this->styles['Lager']->id,
                'abv' => 4.8,
                'description' => 'Cerveza rubia tipo Lager, suave y refrescante'
            ]),
            'Estrella' => Beer::factory()->create([
                'name' => 'Estrella Galicia',
                'brewery_id' => $this->breweries['Estrella']->id,
                'style_id' => $this->styles['Lager']->id,
                'abv' => 5.5,
                'description' => 'Cerveza premium de Galicia'
            ]),
            'BrooklynIPA' => Beer::factory()->create([
                'name' => 'Brooklyn IPA',
                'brewery_id' => $this->breweries['Brooklyn']->id,
                'style_id' => $this->styles['IPA']->id,
                'abv' => 6.9,
                'description' => 'IPA estadounidense muy lupulada'
            ])
        ];

        // Crear check-ins con calificaciones (para probar el filtrado por calificación)
        foreach ($this->beers as $beer) {
            for ($i = 0; $i < 5; $i++) {
                CheckIn::factory()->create([
                    'beer_id' => $beer->id,
                    'user_id' => $this->user->id,
                    'rating' => $beer->id == $this->beers['BrooklynIPA']->id ? 4.5 : 3.5  // Brooklyn IPA mejor calificada
                ]);
            }
        }

        // Crear ubicaciones
        $this->locations = [
            'BarMadrid' => Location::factory()->create([
                'name' => 'Bar Cervecero Madrid',
                'type' => 'bar',
                'country' => 'España',
                'city' => 'Madrid',
                'address' => 'Calle Gran Vía 1',
                'latitude' => 40.416775,
                'longitude' => -3.703790
            ]),
            'BarBarcelona' => Location::factory()->create([
                'name' => 'Cervecería Barcelona',
                'type' => 'bar',
                'country' => 'España',
                'city' => 'Barcelona',
                'address' => 'Rambla de Catalunya 50',
                'latitude' => 41.385064,
                'longitude' => 2.173404
            ]),
            'BarNewYork' => Location::factory()->create([
                'name' => 'Brooklyn Beer Garden',
                'type' => 'bar',
                'country' => 'Estados Unidos',
                'city' => 'Nueva York',
                'address' => '123 Broadway',
                'latitude' => 40.712776,
                'longitude' => -74.005974
            ])
        ];

        // Crear usuarios adicionales
        $this->users = [
            'BeerLover' => User::factory()->create([
                'name' => 'Beer Lover',
                'username' => 'beerlover',
                'bio' => 'Amante de la cerveza artesanal',
                'location' => 'Madrid, España'
            ]),
            'BrewMaster' => User::factory()->create([
                'name' => 'Brew Master',
                'username' => 'brewmaster',
                'bio' => 'Maestro cervecero profesional',
                'location' => 'Barcelona, España'
            ])
        ];
    }

    /**
     * Probar búsqueda general sin filtros.
     */
    public function test_search_with_no_filters()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/search');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'beers' => ['data', 'total'],
                'breweries' => ['data', 'total'],
                'styles' => ['data', 'total'],
                'locations' => ['data', 'total'],
                'users' => ['data', 'total']
            ]);

        // Verificar que se devolvieron resultados para cada tipo
        $this->assertEquals(3, $response['beers']['total']);
        $this->assertEquals(3, $response['breweries']['total']);
        $this->assertEquals(3, $response['styles']['total']);
        $this->assertEquals(3, $response['locations']['total']);
        $this->assertEquals(3, $response['users']['total']); // 1 de setup + 2 creados
    }

    /**
     * Probar búsqueda por término en todos los tipos.
     */
    public function test_search_with_query_term()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/search?q=Madrid');

        $response->assertStatus(200);

        // Verificar que solo se encontraron resultados para ubicaciones y usuarios que contienen "Madrid"
        $this->assertEquals(0, $response['beers']['total']);
        $this->assertEquals(1, $response['breweries']['total']);
        $this->assertEquals(0, $response['styles']['total']);
        $this->assertEquals(1, $response['locations']['total']);
        $this->assertEquals(1, $response['users']['total']);
    }

    /**
     * Probar búsqueda por tipo específico.
     */
    public function test_search_by_specific_type()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/search?type=beers&q=IPA');

        $response->assertStatus(200)
            ->assertJsonStructure(['beers' => ['data', 'total']])
            ->assertJsonMissing(['breweries', 'styles', 'locations', 'users']);

        $this->assertEquals(1, $response['beers']['total']);
        $this->assertEquals('Brooklyn IPA', $response['beers']['data'][0]['name']);
    }

    /**
     * Probar filtrado por país.
     */
    public function test_filter_by_country()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/search?type=breweries&country=España');

        $response->assertStatus(200);
        $this->assertEquals(2, $response['breweries']['total']);
    }

    /**
     * Probar filtrado por ciudad.
     */
    public function test_filter_by_city()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/search?type=locations&city=Madrid');

        $response->assertStatus(200);
        $this->assertEquals(1, $response['locations']['total']);
        $this->assertEquals('Bar Cervecero Madrid', $response['locations']['data'][0]['name']);
    }

    /**
     * Probar filtrado por estilo de cerveza.
     */
    public function test_filter_beers_by_style()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/search?type=beers&style_id=' . $this->styles['IPA']->id);

        $response->assertStatus(200);
        $this->assertEquals(1, $response['beers']['total']);
        $this->assertEquals('Brooklyn IPA', $response['beers']['data'][0]['name']);
    }

    /**
     * Probar filtrado por calificación mínima.
     */
    public function test_filter_beers_by_min_rating()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/search?type=beers&min_rating=4.0');

        $response->assertStatus(200);
        $this->assertEquals(1, $response['beers']['total']);
        $this->assertEquals('Brooklyn IPA', $response['beers']['data'][0]['name']);
    }

    /**
     * Probar ordenamiento por nombre.
     */
    public function test_sort_by_name()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/search?type=beers&sort=name&order=asc');

        $response->assertStatus(200);
        $this->assertEquals('Brooklyn IPA', $response['beers']['data'][0]['name']);
    }

    /**
     * Probar ordenamiento por calificación.
     */
    public function test_sort_by_rating()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/search?type=beers&sort=rating&order=desc');

        $response->assertStatus(200);
        $this->assertEquals('Brooklyn IPA', $response['beers']['data'][0]['name']);
    }

    /**
     * Probar búsqueda por proximidad para ubicaciones.
     */
    public function test_location_search_by_proximity()
    {
        // Coordenadas de Madrid
        $lat = 40.416775;
        $lng = -3.703790;

        $response = $this->actingAs($this->user)
            ->getJson("/api/v1/search?type=locations&lat={$lat}&lng={$lng}&max_distance=10&sort=distance");

        $response->assertStatus(200);
        $this->assertEquals(1, $response['locations']['total']);
        $this->assertEquals('Bar Cervecero Madrid', $response['locations']['data'][0]['name']);
    }

    /**
     * Probar paginación de resultados.
     */
    public function test_pagination()
    {
        // Crear más cervezas para tener suficientes elementos para paginar
        for ($i = 0; $i < 15; $i++) {
            Beer::factory()->create([
                'brewery_id' => $this->breweries['Mahou']->id,
                'style_id' => $this->styles['Lager']->id
            ]);
        }

        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/search?type=beers&per_page=10');

        $response->assertStatus(200);
        $this->assertEquals(18, $response['beers']['total']); // 3 originales + 15 nuevas
        $this->assertCount(10, $response['beers']['data']); // Solo 10 por página
    }
}
