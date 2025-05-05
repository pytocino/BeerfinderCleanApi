<?php

namespace App\Traits;

use App\Models\Report;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Reportable
{
    /**
     * Define la relación con los reportes.
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Determina si el elemento ha sido reportado.
     */
    public function isReported(): bool
    {
        return $this->reports()->exists();
    }

    /**
     * Obtiene la cantidad de reportes pendientes para este elemento.
     */
    public function pendingReportsCount(): int
    {
        return $this->reports()->where('status', '=', 'pending')->count();
    }

    /**
     * Marca todos los reportes como revisados.
     */
    public function markReportsAsReviewed(int $adminId, string $notes): void
    {
        $this->reports()
            ->where('status', '=', 'pending')
            ->update([
                'status' => 'reviewed',
                'reviewed_by' => $adminId,
                'admin_notes' => $notes,
                'resolved_at' => now()
            ]);
    }
}
