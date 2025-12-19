<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
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
        $book = $this->route('book'); // route model binding

        return [
            'title' => 'required|string|max:255',
            'isbn' => [
                'required',
                'string',
                Rule::unique('books', 'isbn')->ignore($book->id),
            ],
            'description' => 'nullable|string',
            'edition' => 'required|string',
            'author_id' => 'required|exists:authors,id',
            'total_copies' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ];
    }
}
