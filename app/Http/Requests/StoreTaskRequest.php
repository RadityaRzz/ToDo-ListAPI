<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'category'     => ['required', Rule::in(['daily', 'school'])],
            'sub_category' => ['nullable', Rule::in(['umum', 'produktif'])],
            'status'       => ['nullable', Rule::in(['pending', 'done'])],
            'due_date'     => ['nullable', 'date'],
            'is_public'    => ['nullable', 'boolean'],
        ];
    }
}
