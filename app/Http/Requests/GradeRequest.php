<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GradeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['numeric',
                'required',
                'max:255',
                Rule::unique('grades', 'name')->ignore($this->route()->id),
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];
    }
}
