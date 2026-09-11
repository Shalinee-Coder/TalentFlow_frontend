<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->user?->name,
            'email' => $this->user?->email,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ] : null,
            'phone' => $this->phone,
            'total_experience' => (float) $this->total_experience,
            'highest_education' => $this->highest_education,
            'current_company' => $this->current_company,
            'current_position' => $this->current_position,
            'location' => $this->location,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
