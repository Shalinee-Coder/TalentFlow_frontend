<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskSubmissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'technical_task_id' => $this->technical_task_id,
            'candidate_id' => $this->candidate_id,
            'candidate' => new CandidateResource($this->whenLoaded('candidate')),
            'submission_content' => $this->submission_content,
            'submission_url' => $this->submission_url,
            'submitted_at' => $this->submitted_at?->toIso8601String(),
            'review_notes' => $this->review_notes,
            'reviewer' => new UserResource($this->whenLoaded('reviewer')),
            'reviewed_at' => $this->reviewed_at?->toIso8601String(),
            'status' => $this->status?->value ?? $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
