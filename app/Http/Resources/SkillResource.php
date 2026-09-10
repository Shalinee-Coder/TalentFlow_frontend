<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'is_required' => $this->whenPivotLoaded('job_skills', function () {
                return (bool) $this->pivot->is_required;
            }),
            'weight' => $this->whenPivotLoaded('job_skills', function () {
                return (int) $this->pivot->weight;
            }),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
