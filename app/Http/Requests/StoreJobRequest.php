<?php

namespace App\Http\Requests;

use App\Enums\JobStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() || $this->user()?->isRecruiter();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'required_experience' => ['required', 'numeric', 'min:0', 'max:50'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'gte:salary_min'],
            'application_deadline' => ['required', 'date', 'after:now'],
            'status' => ['nullable', Rule::enum(JobStatus::class)],
            'skills' => ['nullable', 'array'],
            'skills.*.skill_id' => ['required_with:skills', 'exists:skills,id'],
            'skills.*.is_required' => ['nullable', 'boolean'],
            'skills.*.weight' => ['nullable', 'integer', 'min:1', 'max:10'],
        ];
    }
}
