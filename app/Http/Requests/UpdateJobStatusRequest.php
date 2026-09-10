<?php

namespace App\Http\Requests;

use App\Enums\JobStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $job = $this->route('job');
        return $this->user()?->can('update', $job);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(JobStatus::class)],
        ];
    }
}
