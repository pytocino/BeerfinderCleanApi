<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BreweryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Datos básicos siempre presentes
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'city' => $this->city,
            'logo_url' => $this->logo_url,

            // Contadores y estadísticas
            'beers_count' => $this->when(
                isset($this->beers_count),
                fn() => (int)$this->beers_count
            ),
        ];

        // Datos adicionales para vista detallada
        if (!isset($this->minimal_view)) {
            $data = array_merge($data, [
                'address' => $this->address,
                'founded_year' => $this->when($this->founded_year, (int)$this->founded_year),
                'description' => $this->description,
                'website' => $this->website,
                'type' => $this->type, // (craft, macro, brewpub, etc.)
                'latitude' => $this->when($this->latitude, (float)$this->latitude),
                'longitude' => $this->when($this->longitude, (float)$this->longitude),

                // Social media
                'social_links' => $this->when($this->social_links, function () {
                    return is_array($this->social_links)
                        ? $this->social_links
                        : json_decode($this->social_links, true);
                }),

                // Estadísticas extendidas
                'rating_avg' => $this->when(isset($this->avg_rating), function () {
                    return round((float)$this->avg_rating, 2);
                }),

                // Campos de tiempo
                'created_at' => $this->created_at->format('Y-m-d\TH:i:s.u\Z'),
                'updated_at' => $this->updated_at->format('Y-m-d\TH:i:s.u\Z'),
            ]);

            // Agregar disponibilidad por región si existe
            if ($this->distribution_regions) {
                $data['distribution'] = is_array($this->distribution_regions)
                    ? $this->distribution_regions
                    : json_decode($this->distribution_regions, true);
            }
        }

        // Incluir cervezas destacadas si están cargadas
        if ($this->relationLoaded('beers') && $this->beers->count() > 0) {
            $featuredBeers = $this->beers;

            // Si hay calificación media disponible, ordenar por ella
            if ($featuredBeers->first() && isset($featuredBeers->first()->rating_avg)) {
                $featuredBeers = $featuredBeers->sortByDesc('rating_avg');
            }

            // Limitar a las 5 mejores o más recientes
            $data['featured_beers'] = BeerResource::collection(
                $featuredBeers->take(5)->values()
            );
        }

        // Incluir eventos próximos si están cargados
        if ($this->relationLoaded('events') && $this->events->count() > 0) {
            $data['upcoming_events'] = $this->events
                ->where('date', '>=', now())
                ->sortBy('date')
                ->take(3)
                ->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'name' => $event->name,
                        'date' => $event->date->format('Y-m-d'),
                        'location' => $event->location,
                    ];
                })
                ->values()
                ->all();
        }

        // Si hay galería de fotos
        if ($this->relationLoaded('photos') && $this->photos->count() > 0) {
            $data['photos'] = $this->photos->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'url' => $photo->url,
                    'caption' => $photo->caption,
                ];
            })->values()->all();
        }

        // Incluir premios y reconocimientos si están disponibles
        if ($this->relationLoaded('awards') && $this->awards->count() > 0) {
            $data['awards'] = $this->awards->map(function ($award) {
                return [
                    'name' => $award->name,
                    'year' => $award->year,
                    'category' => $award->category,
                ];
            })->values()->all();
        }

        // Estadísticas de actividad si están disponibles
        if (isset($this->check_ins_count)) {
            $data['activity'] = [
                'check_ins_count' => (int)$this->check_ins_count,
                'unique_users_count' => $this->when(
                    isset($this->unique_users_count),
                    (int)$this->unique_users_count
                ),
                'popularity_trend' => $this->when(
                    $this->popularity_trend,
                    fn() => is_array($this->popularity_trend)
                        ? $this->popularity_trend
                        : json_decode($this->popularity_trend, true)
                ),
            ];
        }

        return $data;
    }

    /**
     * Crear una colección de recursos con vista mínima
     */
    public static function collectionMinimal($resource)
    {
        return $resource->map(function ($item) {
            $item->minimal_view = true;
            return new static($item);
        });
    }
}
