<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasUser;

class PostResource extends JsonResource
{
    use HasUser;

    /**
     * Transformar el recurso en un array.
     */
    public function toArray(Request $request): array
    {
        $userId = $this->getUserId();
        
        return [
            'id' => $this->id,
            'user' => $this->getUserData($userId),
            'beer_review' => $this->getBeerReviewData(),
            'beer' => $this->whenLoaded('beer', fn() => [
                'id' => $this->beer->id,
                'name' => $this->beer->name,
                'brewery' => $this->when($this->beer->relationLoaded('brewery') && $this->beer->brewery, fn() => [
                    'id' => $this->beer->brewery->id,
                    'name' => $this->beer->brewery->name,
                ]),
                'style' => $this->when($this->beer->relationLoaded('style') && $this->beer->style, fn() => [
                    'id' => $this->beer->style->id,
                    'name' => $this->beer->style->name,
                ]),
            ]),
            'location' => $this->whenLoaded('location', fn() => [
                'id' => $this->location->id,
                'name' => $this->location->name,
                'address' => $this->location->address ?? null,
                'city' => $this->location->city ?? null,
                'coordinates' => $this->when($this->location->latitude && $this->location->longitude, fn() => [
                    'latitude' => $this->location->latitude,
                    'longitude' => $this->location->longitude,
                ]),
            ]),
            'content' => $this->content,
            'photo_url' => $this->photo_url,
            'additional_photos' => $this->additional_photos,
            'all_photos' => $this->getAllPhotos(),
            'photos_count' => $this->getTotalPhotosCount(),
            'tags' => $this->getTagsData(),
            'comments_count' => $this->comments_count ?? $this->comments()->count(),
            'likes_count' => $this->likes_count ?? $this->likes()->count(),
            'is_liked' => $this->when($userId, fn() => $this->isLikedBy($userId)),
            'edited' => $this->edited,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Obtiene los datos del usuario optimizados.
     */
    private function getUserData(?int $userId): array
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'username' => $this->user->username,
            'profile_picture' => $this->user->profile_picture,
            'is_me' => $userId && $this->user->id === $userId,
            'is_followed' => $this->when($userId, fn() => 
                $this->user->followers()
                    ->where('users.id', $userId)
                    ->wherePivot('status', 'accepted')
                    ->exists()
            ),
        ];
    }

    /**
     * Obtiene los datos de la reseña de cerveza optimizados.
     */
    private function getBeerReviewData(): ?array
    {
        // Si no hay relación de beerReview cargada, retornar null
        if (!$this->relationLoaded('beerReview') || !$this->beerReview) {
            return null;
        }

        $reviewData = [
            'id' => $this->beerReview->id,
            'rating' => $this->beerReview->rating ?? null,
            'review_text' => $this->beerReview->review_text ?? null,
            'beer' => null,
            'location' => null,
        ];

        // Solo incluir datos de cerveza si la relación está cargada
        if ($this->beerReview->relationLoaded('beer') && $this->beerReview->beer) {
            $reviewData['beer'] = [
                'id' => $this->beerReview->beer->id,
                'name' => $this->beerReview->beer->name,
                'brewery' => $this->when($this->beerReview->beer->relationLoaded('brewery') && $this->beerReview->beer->brewery, fn() => [
                    'id' => $this->beerReview->beer->brewery->id,
                    'name' => $this->beerReview->beer->brewery->name,
                ]),
                'style' => $this->when($this->beerReview->beer->relationLoaded('style') && $this->beerReview->beer->style, fn() => [
                    'id' => $this->beerReview->beer->style->id,
                    'name' => $this->beerReview->beer->style->name,
                ]),
            ];
        }

        // Solo incluir datos de ubicación si la relación está cargada
        if ($this->beerReview->relationLoaded('location') && $this->beerReview->location) {
            $reviewData['location'] = [
                'id' => $this->beerReview->location->id,
                'name' => $this->beerReview->location->name,
                'address' => $this->beerReview->location->address ?? null,
                'city' => $this->beerReview->location->city ?? null,
                'coordinates' => $this->when($this->beerReview->location->latitude && $this->beerReview->location->longitude, fn() => [
                    'latitude' => $this->beerReview->location->latitude,
                    'longitude' => $this->beerReview->location->longitude,
                ]),
            ];
        }

        return $reviewData;
    }

    /**
     * Obtiene los datos de las etiquetas organizados por tipo.
     */
    private function getTagsData(): array
    {
        if (!$this->hasTags()) {
            return [
                'users' => [],
                'beers' => [],
                'locations' => []
            ];
        }

        $organizedTags = $this->getOrganizedTags();

        return [
            'users' => $organizedTags['users']->map(function ($user) {
                return [
                    'id' => $user->id,
                    'username' => $user->username,
                    'name' => $user->name,
                    'profile_picture' => $user->profile_picture,
                ];
            })->toArray(),
            'beers' => $organizedTags['beers']->map(function ($beer) {
                return [
                    'id' => $beer->id,
                    'name' => $beer->name,
                    'brewery' => $beer->brewery ? [
                        'id' => $beer->brewery->id,
                        'name' => $beer->brewery->name,
                    ] : null,
                    'style' => $beer->style ? [
                        'id' => $beer->style->id,
                        'name' => $beer->style->name,
                    ] : null,
                ];
            })->toArray(),
            'locations' => $organizedTags['locations']->map(function ($location) {
                return [
                    'id' => $location->id,
                    'name' => $location->name,
                    'address' => $location->address ?? null,
                    'city' => $location->city ?? null,
                    'coordinates' => [
                        'latitude' => $location->latitude,
                        'longitude' => $location->longitude,
                    ] ?? null,
                ];
            })->toArray(),
        ];
    }
}
