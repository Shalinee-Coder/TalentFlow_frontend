<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_id' => $this->application_id,
            'application' => new ApplicationResource($this->whenLoaded('application')),
            'interviewer' => new UserResource($this->whenLoaded('interviewer')),
            'scheduled_at' => $this->scheduled_at?->toIso8601String(),
            'duration' => $this->duration,
            'end_at' => $this->end_at?->toIso8601String(),
            'meeting_link' => $this->meeting_link,
            'status' => $this->status?->value ?? $this->status,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
