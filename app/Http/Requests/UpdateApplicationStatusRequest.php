<?php

namespace App\Http\Requests;

use App\Enums\ApplicationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $application = $this->route('application');
        return $this->user()?->can('updateStatus', $application);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(ApplicationStatus::class)],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
