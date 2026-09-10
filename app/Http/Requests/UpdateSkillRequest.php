<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() || $this->user()?->isRecruiter();
    }

    public function rules(): array
    {
        $skill = $this->route('skill');
        $skillId = is_object($skill) ? $skill->id : $skill;

        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('skills', 'name')->ignore($skillId)],
            'slug' => ['nullable', 'string', 'max:100', Rule::unique('skills', 'slug')->ignore($skillId)],
        ];
    }
}
