<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
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
        $userId = $this->route('id');

        return [
            'name' => 'required|regex:/^[A-Za-z\s]+$/|max:255',
            'email' => 'required|email|',
            'section_id' => 'required',

            'roll_no' => ['required', 'numeric', Rule::unique('students', 'roll_no')->ignore($this->route()->id)],

        ];
    }
}
