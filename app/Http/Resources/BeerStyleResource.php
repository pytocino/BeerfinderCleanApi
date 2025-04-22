<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeerStyleResource extends JsonResource
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
            'description' => $this->description,

            // Conteo de cervezas (si está disponible)
            'beers_count' => $this->when(
                isset($this->beers_count),
                fn() => (int)$this->beers_count
            ),

            // Campos de tiempo
            'created_at' => $this->created_at->format('Y-m-d\TH:i:s.u\Z'),
            'updated_at' => $this->updated_at->format('Y-m-d\TH:i:s.u\Z'),
        ];

        // Añadir estadísticas extendidas si están disponibles
        if (isset($this->check_ins_count)) {
            $data['check_ins_count'] = (int)$this->check_ins_count;
        }

        // Características adicionales para estilos de cerveza
        $this->addConditionalAttributes($data);

        // Incluir cervezas representativas si están cargadas
        if ($this->relationLoaded('beers') && $this->beers->count() > 0) {
            $data['featured_beers'] = BeerResource::collection(
                $this->beers->sortByDesc('rating_avg')->take(3)
            );
        }

        // Incluir características técnicas comunes del estilo si existen
        $this->addTechnicalProfile($data);

        return $data;
    }

    /**
     * Añade atributos condicionales al array de datos
     */
    protected function addConditionalAttributes(array &$data): void
    {
        // Categoría de estilo (si existe)
        if ($this->category) {
            $data['category'] = $this->category;
        }

        // Región de origen
        if ($this->origin) {
            $data['origin'] = $this->origin;
        }

        // Época de origen (histórico vs contemporáneo)
        if ($this->historical_period) {
            $data['historical_period'] = $this->historical_period;
        }

        // Popularidad relativa (si se calcula)
        if (isset($this->popularity_score)) {
            $data['popularity_score'] = (float)$this->popularity_score;
        }

        // Slug para URLs amigables
        if ($this->slug) {
            $data['slug'] = $this->slug;
        }
    }

    /**
     * Añade perfil técnico del estilo de cerveza
     */
    protected function addTechnicalProfile(array &$data): void
    {
        // Si tenemos algunos parámetros técnicos, crear una sección para ellos
        $technicalFields = [
            'abv_min',
            'abv_max',
            'ibu_min',
            'ibu_max',
            'srm_min',
            'srm_max',
            'og_min',
            'og_max',
            'fg_min',
            'fg_max',
        ];

        $technicalData = [];

        foreach ($technicalFields as $field) {
            if (isset($this->$field)) {
                $technicalData[$field] = $this->formatTechnicalValue($field, $this->$field);
            }
        }

        // Incluir notas de aroma y sabor si existen
        if ($this->aroma_profile) {
            $technicalData['aroma_profile'] = $this->parseJsonField($this->aroma_profile);
        }

        if ($this->flavor_profile) {
            $technicalData['flavor_profile'] = $this->parseJsonField($this->flavor_profile);
        }

        if ($this->appearance) {
            $technicalData['appearance'] = $this->appearance;
        }

        if ($this->mouthfeel) {
            $technicalData['mouthfeel'] = $this->mouthfeel;
        }

        // Si hay datos técnicos, añadirlos al array principal
        if (!empty($technicalData)) {
            $data['technical_profile'] = $technicalData;
        }
    }

    /**
     * Formatea valores técnicos según el tipo de campo
     */
    protected function formatTechnicalValue(string $field, $value)
    {
        // Para valores de ABV, OG, FG, devolver número con precisión adecuada
        if (strpos($field, 'abv_') === 0) {
            return round((float)$value, 1); // 1 decimal para ABV
        }

        if (strpos($field, 'og_') === 0 || strpos($field, 'fg_') === 0) {
            return round((float)$value, 3); // 3 decimales para gravedad
        }

        // Para IBU y SRM, devolver enteros
        if (strpos($field, 'ibu_') === 0 || strpos($field, 'srm_') === 0) {
            return (int)$value;
        }

        // Valor por defecto
        return $value;
    }

    /**
     * Convierte un campo JSON a array/objeto
     */
    protected function parseJsonField($json)
    {
        if (is_string($json)) {
            return json_decode($json, true) ?? $json;
        }
        return $json;
    }

    /**
     * Genera una versión simplificada del recurso para listas
     */
    public static function collection($resource)
    {
        // Si estamos en una vista de lista, podemos personalizar
        // lo que incluimos para cada elemento de la colección
        return parent::collection($resource);
    }
}
