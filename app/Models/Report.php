<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
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
    ];

    /**
     * Los atributos que deben convertirse a fechas.
     *
     * @var array<int, string>
     */
    protected $dates = [
        'resolved_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'admin_notes',
    ];

    /**
     * Obtiene el usuario que reportó el contenido.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el administrador que revisó el reporte.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Obtiene el elemento reportado (polimórfico).
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }
}
