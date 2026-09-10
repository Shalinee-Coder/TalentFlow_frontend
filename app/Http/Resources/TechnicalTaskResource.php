<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TechnicalTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_id' => $this->application_id,
            'application' => new ApplicationResource($this->whenLoaded('application')),
            'recruiter' => new UserResource($this->whenLoaded('recruiter')),
            'title' => $this->title,
            'description' => $this->description,
            'instructions' => $this->instructions,
            'assigned_at' => $this->assigned_at?->toIso8601String(),
            'due_at' => $this->due_at?->toIso8601String(),
            'status' => $this->status?->value ?? $this->status,
            'is_overdue' => $this->isOverdue(),
            'latest_submission' => new TaskSubmissionResource($this->whenLoaded('latestSubmission')),
            'submissions' => TaskSubmissionResource::collection($this->whenLoaded('submissions')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
