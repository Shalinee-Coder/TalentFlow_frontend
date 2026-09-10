<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCandidate();
    }

    public function rules(): array
    {
        return [
            'resume_id' => ['required_without:resume', 'nullable', 'exists:resumes,id'],
            'resume' => [
                'required_without:resume_id',
                'nullable',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:5120',
            ],
        ];
    }
}
