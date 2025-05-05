<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TransactionItem extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_id',
        'itemable_id',
        'itemable_type',
        'description',
        'unit_price',
        'quantity',
        'discount',
        'tax_rate',
        'total',
        'additional_data',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'total' => 'decimal:2',
        'additional_data' => 'array',
    ];

    /**
     * Obtiene la transacción a la que pertenece este elemento.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Obtiene el modelo relacionado con este elemento (polimórfico).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Calcula el subtotal (precio unitario * cantidad).
     *
     * @return float
     */
    public function getSubtotal(): float
    {
        return (float) $this->unit_price * (float) $this->quantity;
    }

    /**
     * Calcula el monto del descuento.
     *
     * @return float
     */
    public function getDiscountAmount(): float
    {
        return (float) $this->discount;
    }

    /**
     * Calcula el monto después de descuentos.
     *
     * @return float
     */
    public function getSubtotalAfterDiscount(): float
    {
        return $this->getSubtotal() - $this->getDiscountAmount();
    }

    /**
     * Calcula el monto de impuestos.
     *
     * @return float
     */
    public function getTaxAmount(): float
    {
        return $this->getSubtotalAfterDiscount() * ((float) $this->tax_rate / 100);
    }

    /**
     * Calcula el total incluyendo impuestos.
     *
     * @return float
     */
    public function calculateTotal(): float
    {
        return $this->getSubtotalAfterDiscount() + $this->getTaxAmount();
    }

    /**
     * Recalcula y actualiza el total basado en los valores actuales.
     *
     * @return bool
     */
    public function recalculateTotal(): bool
    {
        $this->total = $this->calculateTotal();
        return $this->save();
    }

    /**
     * Actualiza la cantidad y recalcula el total.
     *
     * @param float $quantity
     * @return bool
     */
    public function updateQuantity(float $quantity): bool
    {
        $this->quantity = $quantity;
        $this->total = $this->calculateTotal();
        return $this->save();
    }

    /**
     * Actualiza el descuento y recalcula el total.
     *
     * @param float $discount
     * @return bool
     */
    public function updateDiscount(float $discount): bool
    {
        $this->discount = $discount;
        $this->total = $this->calculateTotal();
        return $this->save();
    }

    /**
     * Obtiene el total como string formateado.
     *
     * @return string
     */
    public function getFormattedTotal(): string
    {
        return number_format((float) $this->total, 2);
    }

    /**
     * Obtiene el precio unitario como string formateado.
     *
     * @return string
     */
    public function getFormattedUnitPrice(): string
    {
        return number_format((float) $this->unit_price, 2);
    }

    /**
     * Obtiene el descuento como string formateado.
     *
     * @return string
     */
    public function getFormattedDiscount(): string
    {
        return number_format((float) $this->discount, 2);
    }

    /**
     * Obtiene la tasa de impuestos como string formateado con símbolo %.
     *
     * @return string
     */
    public function getFormattedTaxRate(): string
    {
        return number_format((float) $this->tax_rate, 2) . '%';
    }

    /**
     * Scope para filtrar elementos por transacción.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $transactionId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForTransaction($query, int $transactionId)
    {
        return $query->where('transaction_id', $transactionId);
    }

    /**
     * Scope para filtrar por tipo de elemento.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfItemType($query, string $type)
    {
        return $query->where('itemable_type', $type);
    }

    /**
     * Scope para filtrar elementos con descuento.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithDiscount($query)
    {
        return $query->where('discount', '>', 0);
    }

    /**
     * Scope para ordenar por precio.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByPrice($query, string $direction = 'asc')
    {
        return $query->orderBy('unit_price', $direction);
    }

    /**
     * Scope para ordenar por total.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByTotal($query, string $direction = 'asc')
    {
        return $query->orderBy('total', $direction);
    }
}
