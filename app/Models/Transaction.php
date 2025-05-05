<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_uuid',
        'user_id',
        'transactionable_id',
        'transactionable_type',
        'amount',
        'currency',
        'tax_amount',
        'status',
        'type',
        'payment_method',
        'payment_id',
        'payment_details',
        'notes',
        'processed_by',
        'processed_at',
        'refunded_at',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'payment_details' => 'encrypted:array',
        'processed_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    /**
     * Estados posibles de la transacción.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';
    const STATUS_DISPUTED = 'disputed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Tipos posibles de transacción.
     */
    const TYPE_PURCHASE = 'purchase';
    const TYPE_SUBSCRIPTION = 'subscription';
    const TYPE_REFUND = 'refund';
    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';
    const TYPE_TRANSFER = 'transfer';

    /**
     * Métodos de pago soportados.
     */
    const PAYMENT_STRIPE = 'stripe';
    const PAYMENT_PAYPAL = 'paypal';
    const PAYMENT_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_CREDIT_CARD = 'credit_card';
    const PAYMENT_APPLE_PAY = 'apple_pay';
    const PAYMENT_GOOGLE_PAY = 'google_pay';

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->transaction_uuid) {
                $model->transaction_uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Obtiene el usuario asociado a la transacción.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el modelo relacionado con la transacción (polimórfico).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Obtiene el usuario que procesó la transacción.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Obtiene los elementos asociados a la transacción.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Verifica si la transacción está pendiente.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Verifica si la transacción está completada.
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Verifica si la transacción ha fallado.
     *
     * @return bool
     */
    public function hasFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Verifica si la transacción ha sido reembolsada.
     *
     * @return bool
     */
    public function isRefunded(): bool
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    /**
     * Verifica si la transacción ha sido parcialmente reembolsada.
     *
     * @return bool
     */
    public function isPartiallyRefunded(): bool
    {
        return $this->status === self::STATUS_PARTIALLY_REFUNDED;
    }

    /**
     * Verifica si la transacción está en disputa.
     *
     * @return bool
     */
    public function isDisputed(): bool
    {
        return $this->status === self::STATUS_DISPUTED;
    }

    /**
     * Verifica si la transacción ha sido cancelada.
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Marca la transacción como completada.
     *
     * @param int|null $processorId ID del usuario que procesa
     * @param string|null $notes Notas adicionales
     * @return bool
     */
    public function markAsCompleted(?int $processorId = null, ?string $notes = null): bool
    {
        return $this->updateStatus(self::STATUS_COMPLETED, $processorId, $notes);
    }

    /**
     * Marca la transacción como fallida.
     *
     * @param int|null $processorId ID del usuario que procesa
     * @param string|null $notes Notas adicionales
     * @return bool
     */
    public function markAsFailed(?int $processorId = null, ?string $notes = null): bool
    {
        return $this->updateStatus(self::STATUS_FAILED, $processorId, $notes);
    }

    /**
     * Marca la transacción como reembolsada.
     *
     * @param int|null $processorId ID del usuario que procesa
     * @param string|null $notes Notas adicionales
     * @return bool
     */
    public function markAsRefunded(?int $processorId = null, ?string $notes = null): bool
    {
        $success = $this->updateStatus(self::STATUS_REFUNDED, $processorId, $notes);

        if ($success) {
            $this->refunded_at = now();
            $this->save();
        }

        return $success;
    }

    /**
     * Actualiza el estado de la transacción.
     *
     * @param string $status Nuevo estado
     * @param int|null $processorId ID del usuario que procesa
     * @param string|null $notes Notas adicionales
     * @return bool
     */
    private function updateStatus(string $status, ?int $processorId = null, ?string $notes = null): bool
    {
        $data = ['status' => $status];

        if ($processorId) {
            $data['processed_by'] = $processorId;
            $data['processed_at'] = now();
        }

        if ($notes) {
            $data['notes'] = $this->notes
                ? $this->notes . "\n" . $notes
                : $notes;
        }

        return $this->update($data);
    }

    /**
     * Calcula el total incluyendo impuestos.
     *
     * @return float
     */
    public function getTotal(): float
    {
        return (float) $this->amount + (float) $this->tax_amount;
    }

    /**
     * Obtiene el total como string formateado con moneda.
     *
     * @return string
     */
    public function getFormattedTotal(): string
    {
        return $this->formatMoney($this->getTotal());
    }

    /**
     * Obtiene el monto como string formateado con moneda.
     *
     * @return string
     */
    public function getFormattedAmount(): string
    {
        return $this->formatMoney($this->amount);
    }

    /**
     * Formatea un valor monetario.
     *
     * @param float $amount
     * @return string
     */
    protected function formatMoney($amount): string
    {
        $currencySymbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
        ];

        $symbol = $currencySymbols[$this->currency] ?? $this->currency . ' ';

        return $symbol . number_format((float) $amount, 2);
    }

    /**
     * Scope para filtrar transacciones por estado.
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
     * Scope para filtrar transacciones por tipo.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope para filtrar transacciones por método de pago.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $method
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPaymentMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope para filtrar transacciones exitosas (completadas).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope para filtrar transacciones fallidas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope para filtrar transacciones con monto mayor a un valor.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $amount
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAmountGreaterThan($query, float $amount)
    {
        return $query->where('amount', '>', $amount);
    }

    /**
     * Scope para filtrar transacciones por rango de fecha.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateBetween($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
