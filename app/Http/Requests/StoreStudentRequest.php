<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'full_name' => 'required|string|max:50',
            'roll_no' => 'required|integer',
            'email' => 'required|email|unique:students,email',
            'mobile' => "required|string",
            'department' => "required|string",
            'year_of_study' => 'required',
            'status' => 'nullable',
        ];
    }
}
