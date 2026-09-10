<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() || $this->user()?->isRecruiter();
    }

    public function rules(): array
    {
        return [
            'interviewer_id' => ['nullable', 'exists:users,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'duration' => ['required', 'integer', 'min:15', 'max:240'], // 15 mins to 4 hours
            'meeting_link' => ['nullable', 'url', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
