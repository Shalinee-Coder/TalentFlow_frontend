<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadResumeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCandidate() || $this->user()?->isRecruiter() || $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'candidate_id' => [
                Rule::requiredIf(fn () => !$this->user()?->isCandidate()),
                'nullable',
                'integer',
                'exists:candidates,id',
            ],
            'resume' => [
                'required',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:5120', // 5MB limit
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'resume.required' => 'A PDF resume file is required.',
            'resume.mimes' => 'The resume must be a file of type: pdf.',
            'resume.mimetypes' => 'The uploaded file must be a valid PDF document.',
            'resume.max' => 'The resume file size must not exceed 5MB.',
        ];
    }
}
