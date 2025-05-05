<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionItemResource extends JsonResource
{
    /**
     * Transformar el recurso en un array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,
            'itemable_type' => $this->itemable_type,
            'itemable_id' => $this->itemable_id,
            'itemable' => $this->whenLoaded('itemable'),
            'description' => $this->description,
            'unit_price' => $this->unit_price,
            'formatted_unit_price' => $this->getFormattedUnitPrice(),
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'formatted_discount' => $this->getFormattedDiscount(),
            'tax_rate' => $this->tax_rate,
            'formatted_tax_rate' => $this->getFormattedTaxRate(),
            'subtotal' => $this->getSubtotal(),
            'subtotal_after_discount' => $this->getSubtotalAfterDiscount(),
            'tax_amount' => $this->getTaxAmount(),
            'total' => $this->total,
            'formatted_total' => $this->getFormattedTotal(),
            'additional_data' => $this->additional_data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
