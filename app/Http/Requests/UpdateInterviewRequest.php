<?php

namespace App\Http\Requests;

use App\Enums\InterviewStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        $interview = $this->route('interview');
        return $this->user()?->can('update', $interview);
    }

    public function rules(): array
    {
        return [
            'interviewer_id' => ['sometimes', 'required', 'exists:users,id'],
            'scheduled_at' => ['sometimes', 'required', 'date'],
            'duration' => ['sometimes', 'required', 'integer', 'min:15', 'max:240'],
            'meeting_link' => ['nullable', 'url', 'max:500'],
            'status' => ['sometimes', 'required', Rule::enum(InterviewStatus::class)],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
