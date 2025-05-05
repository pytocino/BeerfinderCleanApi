<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasUser;

class TransactionResource extends JsonResource
{
    use HasUser;

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
            'transaction_uuid' => $this->transaction_uuid,
            'user' => new UserResource($this->whenLoaded('user')),
            'transactionable_type' => $this->transactionable_type,
            'transactionable_id' => $this->transactionable_id,
            'transactionable' => $this->whenLoaded('transactionable'),
            'amount' => $this->amount,
            'formatted_amount' => $this->getFormattedAmount(),
            'tax_amount' => $this->tax_amount,
            'currency' => $this->currency,
            'total' => $this->getTotal(),
            'formatted_total' => $this->getFormattedTotal(),
            'status' => $this->status,
            'type' => $this->type,
            'payment_method' => $this->payment_method,
            'payment_id' => $this->payment_id,
            'payment_details' => $this->payment_details,
            'notes' => $this->notes,
            'processed_by' => $this->processed_by,
            'processor' => new UserResource($this->whenLoaded('processor')),
            'processed_at' => $this->processed_at,
            'refunded_at' => $this->refunded_at,
            'items' => TransactionItemResource::collection($this->whenLoaded('items')),
            'is_pending' => $this->isPending(),
            'is_completed' => $this->isCompleted(),
            'is_failed' => $this->hasFailed(),
            'is_refunded' => $this->isRefunded(),
            'is_partially_refunded' => $this->isPartiallyRefunded(),
            'is_disputed' => $this->isDisputed(),
            'is_cancelled' => $this->isCancelled(),
            'belongs_to_authenticated_user' => $this->belongsToAuthenticatedUser(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
