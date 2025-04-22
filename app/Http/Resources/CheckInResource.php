<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckInResource extends JsonResource
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
            'rating' => round((float)$this->rating, 1),
            'comment' => $this->comment,
            'photo_url' => $this->photo_url,

            // Conteo de interacciones
            'likes_count' => $this->when(
                isset($this->likes_count),
                fn() => (int)$this->likes_count,
                0
            ),
            'comments_count' => $this->when(
                isset($this->comments_count),
                fn() => (int)$this->comments_count,
                0
            ),

            // Estado del usuario actual
            'is_liked' => $this->when(
                isset($this->is_liked),
                fn() => (bool)$this->is_liked,
                false
            ),

            // Información adicional
            'serving' => $this->when($this->serving, $this->serving),

            // Campos de tiempo
            'created_at' => $this->created_at->format('Y-m-d\TH:i:s.u\Z'),
            'updated_at' => $this->when(
                $this->updated_at,
                fn() => $this->updated_at->format('Y-m-d\TH:i:s.u\Z')
            ),
        ];

        // Añadir información de compra si existe
        if ($this->purchase_price) {
            $data['purchase'] = [
                'price' => (float)$this->purchase_price,
                'currency' => $this->purchase_currency ?? 'EUR'
            ];
        }

        // Añadir notas de sabor si existen
        if ($this->flavor_notes) {
            $data['flavor_notes'] = is_string($this->flavor_notes)
                ? json_decode($this->flavor_notes)
                : $this->flavor_notes;
        }

        // Añadir relaciones cuando están cargadas
        $this->appendRelations($data);

        // Añadir metadatos adicionales para vistas detalladas
        if ($this->shouldIncludeDetails()) {
            $data = $this->appendDetailedInfo($data);
        }

        return $data;
    }

    /**
     * Añade las relaciones cargadas al array de datos
     */
    protected function appendRelations(array &$data): void
    {
        // Usuario
        if ($this->relationLoaded('user')) {
            $data['user'] = [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'username' => $this->user->username ?? null,
                'profile_picture' => $this->user->profile_picture,
            ];
        }

        // Cerveza
        if ($this->relationLoaded('beer')) {
            $beerData = [
                'id' => $this->beer->id,
                'name' => $this->beer->name,
                'image_url' => $this->beer->image_url,
            ];

            // Añadir cervecería si está cargada en la relación anidada
            if ($this->beer->relationLoaded('brewery')) {
                $beerData['brewery'] = [
                    'id' => $this->beer->brewery->id,
                    'name' => $this->beer->brewery->name,
                ];
            }

            // Añadir estilo si está cargado en la relación anidada
            if ($this->beer->relationLoaded('style')) {
                $beerData['style'] = [
                    'id' => $this->beer->style->id,
                    'name' => $this->beer->style->name,
                ];
            }

            $data['beer'] = $beerData;
        }

        // Ubicación
        if ($this->relationLoaded('location') && $this->location) {
            $data['location'] = [
                'id' => $this->location->id,
                'name' => $this->location->name,
                'address' => $this->when(
                    $this->location->address,
                    $this->location->address
                ),
            ];

            if ($this->location->latitude && $this->location->longitude) {
                $data['location']['coordinates'] = [
                    'latitude' => (float)$this->location->latitude,
                    'longitude' => (float)$this->location->longitude,
                ];
            }
        }

        // Comentarios
        if ($this->relationLoaded('comments') && $this->comments->count() > 0) {
            $data['comments'] = $this->comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'profile_picture' => $comment->user->profile_picture,
                    ],
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->format('Y-m-d\TH:i:s.u\Z'),
                ];
            })->values();
        }

        // Likes (si se cargaron, mostrar quién ha dado like)
        if ($this->relationLoaded('likes') && $this->likes->count() > 0) {
            $data['recent_likes'] = $this->likes
                ->take(5)
                ->map(function ($like) {
                    return [
                        'id' => $like->user->id,
                        'name' => $like->user->name,
                        'profile_picture' => $like->user->profile_picture,
                    ];
                })
                ->values();
        }
    }

    /**
     * Añade información detallada para vistas específicas
     */
    protected function appendDetailedInfo(array $data): array
    {
        // Añadir etiquetas si existen
        if ($this->tags) {
            $data['tags'] = is_string($this->tags)
                ? json_decode($this->tags)
                : $this->tags;
        }

        // Añadir metadatos del dispositivo/app si existen
        if ($this->app_version) {
            $data['metadata'] = array_filter([
                'app_version' => $this->app_version,
                'device' => $this->device,
                'platform' => $this->platform,
            ]);
        }

        // Información de edición
        if ($this->edited) {
            $data['edited'] = (bool)$this->edited;
            if ($this->edited_at) {
                $data['edited_at'] = $this->edited_at->format('Y-m-d\TH:i:s.u\Z');
            }
        }

        return $data;
    }

    /**
     * Determina si se debe incluir información detallada
     */
    protected function shouldIncludeDetails(): bool
    {
        // Incluir detalles si explícitamente se solicitan o si
        // estamos viendo un check-in específico (no una lista)
        return request()->has('details') ||
            (request()->route() && request()->route()->getName() === 'check-ins.show');
    }

    /**
     * Personaliza la colección de recursos
     */
    public static function collection($resource)
    {
        // Para colecciones usamos la implementación estándar
        return parent::collection($resource);
    }
}
