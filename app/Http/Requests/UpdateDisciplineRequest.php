<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDisciplineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255|unique:disciplines,name,' . $this->discipline->id,
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'A discipline with this name already exists.',
        ];
    }
}
