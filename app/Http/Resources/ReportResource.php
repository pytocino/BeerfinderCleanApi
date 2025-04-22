<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Datos básicos del reporte
        $data = [
            'id' => $this->id,
            'reason' => $this->reason,
            'details' => $this->details,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d\TH:i:s.u\Z'),
        ];

        // Información del usuario que reportó (si se cargó la relación)
        if ($this->relationLoaded('user')) {
            $data['reporter'] = [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ];
        }

        // Información básica sobre el contenido reportado
        $data['content_type'] = $this->reportable_type;
        $data['content_id'] = $this->reportable_id;

        // Si el reporte fue resuelto
        if ($this->resolved_at) {
            $data['resolved_at'] = $this->resolved_at->format('Y-m-d\TH:i:s.u\Z');

            // Información del administrador que lo revisó (si se cargó la relación)
            if ($this->relationLoaded('reviewer')) {
                $data['reviewer'] = [
                    'id' => $this->reviewer->id,
                    'name' => $this->reviewer->name,
                ];
            }
        }

        // Para administradores, incluir notas administrativas
        if ($request->user() && $request->user()->is_admin) {
            $data['admin_notes'] = $this->admin_notes;
        }

        return $data;
    }
}
