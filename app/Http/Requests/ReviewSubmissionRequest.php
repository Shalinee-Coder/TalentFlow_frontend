<?php

namespace App\Http\Requests;

use App\Enums\TaskSubmissionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $submission = $this->route('submission');
        return $this->user()?->can('review', $submission);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in([TaskSubmissionStatus::ACCEPTED->value, TaskSubmissionStatus::REJECTED->value])],
            'review_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
