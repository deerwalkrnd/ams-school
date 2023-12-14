<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Your authorization logic here, e.g., checking if the user has the necessary permissions.
        return true; // Update this based on your requirements.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|regex:/^[A-Za-z\s]+$/|max:255',
            'grade_id' => 'required',
            'user_id' => 'required',
        ];
    }
}
