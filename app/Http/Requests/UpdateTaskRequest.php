<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => ['sometimes', 'required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'category'     => ['sometimes', 'required', Rule::in(['daily', 'school'])],
            'sub_category' => ['nullable', Rule::in(['umum', 'produktif'])],
            'status'       => ['nullable', Rule::in(['pending', 'done'])],
            'due_date'     => ['nullable', 'date'],
            'is_public'    => ['nullable', 'boolean'],
        ];
    }
}
