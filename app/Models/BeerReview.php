<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeerReview extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'beer_reviews';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'beer_id',
        'location_id',
        'post_id',
        'rating',
        'review_text',
        'serving_type',
        'purchase_price',
        'purchase_currency',
        'is_public',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'float',
        'purchase_price' => 'float',
        'avg_rating' => 'float',
        'ratings_count' => 'integer',
        'is_public' => 'boolean',
    ];

    /**
     * Las opciones disponibles para el tipo de servicio.
     *
     * @var array<string>
     */
    public static $servingTypes = [
        'bottle',
        'can',
        'draft',
        'growler',
        'taster',
        'crowler',
    ];

    /**
     * Obtiene el usuario que hizo la reseña.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la cerveza que se reseñó.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function beer(): BelongsTo
    {
        return $this->belongsTo(Beer::class);
    }

    /**
     * Obtiene la ubicación donde se probó la cerveza.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Obtiene el post asociado a esta reseña.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Obtiene el tipo de servicio formateado.
     *
     * @return string|null
     */
    public function getFormattedServingType(): ?string
    {
        if (!$this->serving_type) {
            return null;
        }

        $labels = [
            'bottle' => 'Botella',
            'can' => 'Lata',
            'draft' => 'Barril',
            'growler' => 'Growler',
            'taster' => 'Degustación',
            'crowler' => 'Crowler',
        ];

        return $labels[$this->serving_type] ?? $this->serving_type;
    }

    /**
     * Formatea el precio para visualización.
     *
     * @return string|null
     */
    public function getFormattedPrice(): ?string
    {
        if (!$this->purchase_price) {
            return null;
        }

        return number_format($this->purchase_price, 2) . ' ' . $this->purchase_currency;
    }

    /**
     * Verifica si la reseña tiene texto.
     *
     * @return bool
     */
    public function hasReviewText(): bool
    {
        return !empty($this->review_text);
    }

    /**
     * Obtiene un fragmento del texto de la reseña.
     *
     * @param int $length
     * @return string|null
     */
    public function getReviewExcerpt(int $length = 100): ?string
    {
        if (!$this->hasReviewText()) {
            return null;
        }

        if (strlen($this->review_text) <= $length) {
            return $this->review_text;
        }

        return substr($this->review_text, 0, $length) . '...';
    }

    /**
     * Obtiene las estrellas para mostrar en la interfaz.
     *
     * @return array Array con estado de cada estrella (llena, media o vacía)
     */
    public function getRatingStars(): array
    {
        $stars = [];
        $fullRating = floor($this->rating);
        $halfStar = ($this->rating - $fullRating) >= 0.5;

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $fullRating) {
                $stars[] = 'full';
            } elseif ($i == $fullRating + 1 && $halfStar) {
                $stars[] = 'half';
            } else {
                $stars[] = 'empty';
            }
        }

        return $stars;
    }

    /**
     * Determina si es una valoración alta (4 o más).
     *
     * @return bool
     */
    public function isHighRating(): bool
    {
        return $this->rating >= 4.0;
    }

    /**
     * Determina si es una valoración baja (2 o menos).
     *
     * @return bool
     */
    public function isLowRating(): bool
    {
        return $this->rating <= 2.0;
    }

    /**
     * Calcula y actualiza la valoración media en la cerveza asociada.
     *
     * @return float|null
     */
    public function updateBeerRating(): ?float
    {
        $beer = $this->beer;

        $avgRating = self::where('beer_id', $this->beer_id)->avg('rating');
        $count = self::where('beer_id', $this->beer_id)->count();

        $beer->update([
            'avg_rating' => $avgRating,
            'ratings_count' => $count
        ]);

        return $avgRating;
    }

    /**
     * Crea un post asociado a esta reseña.
     *
     * @param array $postData
     * @return Post|null
     */
    public function createAssociatedPost(array $postData): ?Post
    {
        if ($this->post_id) {
            return null;
        }

        $postData['user_id'] = $this->user_id;

        $post = Post::create($postData);
        $this->update(['post_id' => $post->id]);

        return $post;
    }

    /**
     * Scope para obtener solo reseñas públicas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope para filtrar por valoración mínima.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $minRating
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMinRating($query, float $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Scope para filtrar por valoración máxima.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $maxRating
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMaxRating($query, float $maxRating)
    {
        return $query->where('rating', '<=', $maxRating);
    }

    /**
     * Scope para filtrar por tipo de servicio.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $servingType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithServingType($query, string $servingType)
    {
        return $query->where('serving_type', $servingType);
    }

    /**
     * Scope para filtrar por ubicación.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $locationId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAtLocation($query, int $locationId)
    {
        return $query->where('location_id', $locationId);
    }

    /**
     * Scope para obtener solo reseñas con texto.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithText($query)
    {
        return $query->whereNotNull('review_text')
            ->where('review_text', '!=', '');
    }
}
