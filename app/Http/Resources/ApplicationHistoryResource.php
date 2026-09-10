<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_id' => $this->application_id,
            'from_status' => $this->from_status?->value ?? $this->from_status,
            'to_status' => $this->to_status?->value ?? $this->to_status,
            'changed_by' => new UserResource($this->whenLoaded('changedByUser')),
            'changed_at' => $this->changed_at?->toIso8601String(),
            'remarks' => $this->remarks,
        ];
    }
}
