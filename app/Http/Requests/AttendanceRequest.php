<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (Auth::user()->hasRole("admin") || Auth::user()->hasRole("teacher")) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'attendances'                           => ['required', 'array'],
            'attendances.*'                         => ['required', 'array'],
            'attendances.*.rollNo'                  => ['required', 'exists:students,roll_no'],
            'attendances.*.attendanceStatus'        => ['required'],
            'attendances.*.attendanceStatus.present'=> ['required', 'numeric'],
            'attendances.*.attendanceStatus.absent' => ['required', 'numeric'],
        ];

        return $rules;
    }
}
