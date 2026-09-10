<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        $task = $this->route('task');
        return $this->user()?->can('submit', $task);
    }

    public function rules(): array
    {
        return [
            'submission_url' => ['required', 'url', 'max:500'],
            'submission_content' => ['nullable', 'string'],
        ];
    }
}
