<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() || $this->user()?->isRecruiter();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:skills,name'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:skills,slug'],
        ];
    }
}
