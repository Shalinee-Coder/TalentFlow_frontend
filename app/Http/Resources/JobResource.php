<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'recruiter' => new UserResource($this->whenLoaded('recruiter')),
            'title' => $this->title,
            'department' => $this->department,
            'description' => $this->description,
            'required_experience' => (float) $this->required_experience,
            'salary_min' => $this->salary_min !== null ? (float) $this->salary_min : null,
            'salary_max' => $this->salary_max !== null ? (float) $this->salary_max : null,
            'application_deadline' => $this->application_deadline?->toIso8601String(),
            'status' => $this->status?->value ?? $this->status,
            'is_open' => $this->isAcceptingApplications(),
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
            'applications_count' => $this->whenCounted('applications'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
