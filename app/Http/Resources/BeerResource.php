<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use App\Models\CheckIn;
use Carbon\Carbon;

class BeerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // Datos básicos siempre presentes
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'brewery' => $this->whenLoaded('brewery', function () {
                return [
                    'id' => $this->brewery->id,
                    'name' => $this->brewery->name,
                    'country' => $this->brewery->country,
                    'logo_url' => $this->brewery->logo_url,
                ];
            }),
            'style' => $this->whenLoaded('style', function () {
                return [
                    'id' => $this->style->id,
                    'name' => $this->style->name,
                ];
            }),
            'abv' => $this->formatAbv(),
            'ibu' => $this->ibu,
            'description' => $this->description,
            'image_url' => $this->image_url,

            // Estadísticas
            'rating_avg' => $this->formatRating(),
            'check_ins_count' => $this->when(
                isset($this->check_ins_count),
                $this->check_ins_count
            ),

            // Estado de usuario actual
            'is_favorite' => $this->when(
                isset($this->is_favorite),
                (bool)$this->is_favorite
            ),

            // Información adicional condicional
            'seasonal' => $this->when($this->seasonal, $this->seasonal),
            'year' => $this->when($this->year, $this->year),
            'flavor_profile' => $this->when($this->flavor_profile, function () {
                return json_decode($this->flavor_profile) ?? null;
            }),

            // Campos de tiempo
            'created_at' => $this->when($this->created_at, function () {
                return $this->created_at->format('Y-m-d\TH:i:s.u\Z');
            }),
            'updated_at' => $this->when($this->updated_at, function () {
                return $this->updated_at->format('Y-m-d\TH:i:s.u\Z');
            }),
        ];

        // Incluir estadísticas avanzadas si están disponibles
        if (isset($this->check_ins_avg_rating)) {
            $data['stats'] = $this->getExtendedStats();
        }

        // Incluir atributos para ordenamiento y filtrado en UI
        $data['attributes'] = $this->getAttributes();

        // Incluir última actividad si se han cargado los check-ins
        if ($this->relationLoaded('checkIns') && $this->checkIns->count() > 0) {
            $data['last_activity'] = $this->getLastActivity();
        }

        // Incluir cervezas relacionadas si están cargadas
        if ($this->relationLoaded('relatedBeers')) {
            $data['related_beers'] = BeerResource::collection($this->relatedBeers);
        }

        return $data;
    }

    /**
     * Formatea el valor de ABV con precisión consistente
     */
    protected function formatAbv(): ?float
    {
        if ($this->abv === null) return null;
        return round((float)$this->abv, 1);
    }

    /**
     * Formatea la calificación con precisión consistente
     */
    protected function formatRating(): ?float
    {
        if (isset($this->check_ins_avg_rating)) {
            return round((float)$this->check_ins_avg_rating, 2);
        } elseif (isset($this->rating_avg)) {
            return round((float)$this->rating_avg, 2);
        }
        return null;
    }

    /**
     * Obtiene estadísticas extendidas de la cerveza
     */
    protected function getExtendedStats(): array
    {
        $stats = [
            'avg_rating' => $this->formatRating(),
            'total_check_ins' => $this->check_ins_count ?? 0,
        ];

        // Si hay datos de distribución de calificaciones
        if (isset($this->ratings_distribution)) {
            $stats['ratings_distribution'] = json_decode($this->ratings_distribution, true);
        } else {
            // Distribución por defecto (para mantener consistencia en la API)
            $stats['ratings_distribution'] = [
                '5' => 0,
                '4' => 0,
                '3' => 0,
                '2' => 0,
                '1' => 0,
            ];
        }

        // Si hay datos de popularidad mensual
        if (isset($this->monthly_popularity)) {
            $stats['monthly_trend'] = json_decode($this->monthly_popularity, true);
        }

        // Información adicional si está disponible
        if (isset($this->popularity_score)) {
            $stats['popularity_score'] = $this->popularity_score;
        }

        return $stats;
    }

    /**
     * Obtiene atributos adicionales para representación y filtrado
     */
    protected function getAttributes(): array
    {
        $attributes = [
            'abv_category' => $this->getAbvCategory(),
            'ibu_category' => $this->getIbuCategory(),
            'color_srm' => $this->color_srm ?? null,
        ];

        return array_filter($attributes);
    }

    /**
     * Categoriza el ABV para facilitar filtrado
     */
    protected function getAbvCategory(): ?string
    {
        if ($this->abv === null) return null;

        $abv = (float)$this->abv;

        if ($abv < 0.5) return 'non_alcoholic';
        if ($abv < 4.0) return 'light';
        if ($abv < 6.0) return 'standard';
        if ($abv < 8.0) return 'strong';
        if ($abv < 12.0) return 'very_strong';
        return 'extreme';
    }

    /**
     * Categoriza el IBU para facilitar filtrado
     */
    protected function getIbuCategory(): ?string
    {
        if ($this->ibu === null) return null;

        $ibu = (int)$this->ibu;

        if ($ibu < 20) return 'low';
        if ($ibu < 40) return 'medium';
        if ($ibu < 70) return 'high';
        return 'very_high';
    }

    /**
     * Obtiene información sobre la última actividad de esta cerveza
     */
    protected function getLastActivity(): array
    {
        $lastCheckIn = $this->checkIns->sortByDesc('created_at')->first();

        if (!$lastCheckIn) {
            return ['type' => 'none'];
        }

        return [
            'type' => 'check_in',
            'date' => $lastCheckIn->created_at->format('Y-m-d\TH:i:s.u\Z'),
            'user' => [
                'id' => $lastCheckIn->user->id,
                'name' => $lastCheckIn->user->name,
                'profile_picture' => $lastCheckIn->user->profile_picture,
            ],
            'rating' => $lastCheckIn->rating,
            'check_in_id' => $lastCheckIn->id
        ];
    }
}
