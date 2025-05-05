<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasUser;

class ReportResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'reportable_type' => $this->reportable_type,
            'reportable_id' => $this->reportable_id,
            'reportable' => $this->whenLoaded('reportable'),
            'reason' => $this->reason,
            'formatted_reason' => $this->getFormattedReason(),
            'details' => $this->details,
            'status' => $this->status,
            'formatted_status' => $this->getFormattedStatus(),
            'reviewed_by' => $this->reviewed_by,
            'reviewer' => new UserResource($this->whenLoaded('reviewer')),
            'admin_notes' => $this->admin_notes,
            'resolved_at' => $this->resolved_at,
            'public' => $this->public,
            'screenshot_url' => $this->screenshot_url,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'is_pending' => $this->isPending(),
            'is_reviewed' => $this->isReviewed(),
            'is_rejected' => $this->isRejected(),
            'is_actioned' => $this->isActioned(),
            'is_resolved' => $this->isResolved(),
            'belongs_to_authenticated_user' => $this->belongsToAuthenticatedUser(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
