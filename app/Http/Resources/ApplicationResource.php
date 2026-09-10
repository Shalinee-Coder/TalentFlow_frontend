<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_id' => $this->job_id,
            'job' => new JobResource($this->whenLoaded('job')),
            'candidate_id' => $this->candidate_id,
            'candidate' => new CandidateResource($this->whenLoaded('candidate')),
            'resume_id' => $this->resume_id,
            'resume' => new ResumeResource($this->whenLoaded('resume')),
            'score' => (float) $this->score,
            'current_status' => $this->current_status?->value ?? $this->current_status,
            'allowed_next_statuses' => $this->current_status instanceof \App\Enums\ApplicationStatus
                ? array_map(fn($s) => $s->value, $this->current_status->allowedTransitions())
                : [],
            'applied_at' => $this->applied_at?->toIso8601String(),
            'status_histories' => ApplicationHistoryResource::collection($this->whenLoaded('statusHistories')),
            'interviews' => InterviewResource::collection($this->whenLoaded('interviews')),
            'technical_tasks' => TechnicalTaskResource::collection($this->whenLoaded('technicalTasks')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
