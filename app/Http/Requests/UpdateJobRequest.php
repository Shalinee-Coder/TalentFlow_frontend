<?php

namespace App\Http\Requests;

use App\Enums\JobStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        $job = $this->route('job');
        return $this->user()?->can('update', $job);
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'department' => ['sometimes', 'required', 'string', 'max:100'],
            'description' => ['sometimes', 'required', 'string'],
            'required_experience' => ['sometimes', 'required', 'numeric', 'min:0', 'max:50'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'gte:salary_min'],
            'application_deadline' => ['sometimes', 'required', 'date'],
            'status' => ['sometimes', 'required', Rule::enum(JobStatus::class)],
            'skills' => ['nullable', 'array'],
            'skills.*.skill_id' => ['required_with:skills', 'exists:skills,id'],
            'skills.*.is_required' => ['nullable', 'boolean'],
            'skills.*.weight' => ['nullable', 'integer', 'min:1', 'max:10'],
        ];
    }
}
