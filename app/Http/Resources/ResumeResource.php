<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResumeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'candidate_id' => $this->candidate_id,
            'original_filename' => $this->original_filename,
            'mime_type' => $this->mime_type,
            'file_size' => $this->file_size,
            'file_size_formatted' => round($this->file_size / 1024, 2) . ' KB',
            'processing_status' => $this->processing_status?->value ?? $this->processing_status,
            'extracted_data' => $this->extracted_data,
            'download_url' => url("/api/v1/resumes/{$this->id}/download"),
            'processed_at' => $this->processed_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
