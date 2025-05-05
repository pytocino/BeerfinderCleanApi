<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Report extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'details',
        'status',
        'reviewed_by',
        'admin_notes',
        'resolved_at',
        'public',
        'screenshot_url',
        'ip_address',
        'user_agent',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'public' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    /**
     * Razones posibles para reportes.
     */
    const REASON_SPAM = 'spam';
    const REASON_OFFENSIVE = 'offensive';
    const REASON_INAPPROPRIATE = 'inappropriate';
    const REASON_HARASSMENT = 'harassment';
    const REASON_FAKE = 'fake';
    const REASON_COPYRIGHT = 'copyright';
    const REASON_OTHER = 'other';

    /**
     * Estados posibles para reportes.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ACTIONED = 'actioned';

    /**
     * Proteger la IP con encriptación.
     */
    protected function ipAddress(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Crypt::decryptString($value) : null,
            set: fn($value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    /**
     * Proteger el User Agent con encriptación.
     */
    protected function userAgent(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Crypt::decryptString($value) : null,
            set: fn($value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    /**
     * Obtiene el usuario que hizo el reporte.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el usuario administrador que revisó el reporte.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Obtiene el elemento reportado (polimórfico).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Verifica si el reporte está pendiente.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Verifica si el reporte ha sido revisado.
     *
     * @return bool
     */
    public function isReviewed(): bool
    {
        return $this->status === self::STATUS_REVIEWED;
    }

    /**
     * Verifica si el reporte ha sido rechazado.
     *
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Verifica si se ha tomado acción sobre el reporte.
     *
     * @return bool
     */
    public function isActioned(): bool
    {
        return $this->status === self::STATUS_ACTIONED;
    }

    /**
     * Verifica si el reporte ha sido resuelto (revisado, rechazado o accionado).
     *
     * @return bool
     */
    public function isResolved(): bool
    {
        return !$this->isPending();
    }

    /**
     * Marca el reporte como revisado.
     *
     * @param int $adminId
     * @param string|null $notes
     * @return bool
     */
    public function markAsReviewed(int $adminId, ?string $notes = null): bool
    {
        return $this->updateStatus(self::STATUS_REVIEWED, $adminId, $notes);
    }

    /**
     * Marca el reporte como rechazado.
     *
     * @param int $adminId
     * @param string|null $notes
     * @return bool
     */
    public function markAsRejected(int $adminId, ?string $notes = null): bool
    {
        return $this->updateStatus(self::STATUS_REJECTED, $adminId, $notes);
    }

    /**
     * Marca el reporte como accionado.
     *
     * @param int $adminId
     * @param string|null $notes
     * @return bool
     */
    public function markAsActioned(int $adminId, ?string $notes = null): bool
    {
        return $this->updateStatus(self::STATUS_ACTIONED, $adminId, $notes);
    }

    /**
     * Actualiza el estado del reporte.
     *
     * @param string $status
     * @param int $adminId
     * @param string|null $notes
     * @return bool
     */
    private function updateStatus(string $status, int $adminId, ?string $notes = null): bool
    {
        return $this->update([
            'status' => $status,
            'reviewed_by' => $adminId,
            'admin_notes' => $notes,
            'resolved_at' => now(),
        ]);
    }

    /**
     * Obtiene el texto de razón formateado.
     *
     * @return string
     */
    public function getFormattedReason(): string
    {
        $reasons = [
            self::REASON_SPAM => 'Spam',
            self::REASON_OFFENSIVE => 'Contenido ofensivo',
            self::REASON_INAPPROPRIATE => 'Contenido inapropiado',
            self::REASON_HARASSMENT => 'Acoso',
            self::REASON_FAKE => 'Información falsa',
            self::REASON_COPYRIGHT => 'Infracción de copyright',
            self::REASON_OTHER => 'Otro motivo',
        ];

        return $reasons[$this->reason] ?? $this->reason;
    }

    /**
     * Obtiene el texto de estado formateado.
     *
     * @return string
     */
    public function getFormattedStatus(): string
    {
        $statuses = [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_REVIEWED => 'Revisado',
            self::STATUS_REJECTED => 'Rechazado',
            self::STATUS_ACTIONED => 'Acción tomada',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Scope para filtrar por estado.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para filtrar reportes pendientes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope para filtrar reportes resueltos.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeResolved($query)
    {
        return $query->whereIn('status', [
            self::STATUS_REVIEWED,
            self::STATUS_REJECTED,
            self::STATUS_ACTIONED
        ]);
    }

    /**
     * Scope para filtrar por tipo de contenido reportado.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('reportable_type', $type);
    }

    /**
     * Scope para filtrar por razón.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $reason
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByReason($query, string $reason)
    {
        return $query->where('reason', $reason);
    }

    /**
     * Scope para filtrar reportes revisados por un administrador específico.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $adminId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReviewedBy($query, int $adminId)
    {
        return $query->where('reviewed_by', $adminId);
    }

    /**
     * Anonimiza los datos sensibles después de un período de tiempo.
     *
     * @return bool
     */
    public function anonymize(): bool
    {
        return $this->update([
            'ip_address' => null,
            'user_agent' => null
        ]);
    }
}
