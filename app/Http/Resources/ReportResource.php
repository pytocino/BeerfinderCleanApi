<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'reportable_type' => $this->reportable_type,
            'reportable_id' => $this->reportable_id,
            'reason' => $this->reason,
            'details' => $this->details,
            'status' => $this->status,
            'reviewed_by' => $this->reviewed_by,
            'resolved_at' => $this->resolved_at,
            'public' => $this->public,
            'screenshot_url' => $this->screenshot_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->user,
            'reviewer' => $this->reviewer,
            'reportable' => $this->reportable,
        ];
    }
}
