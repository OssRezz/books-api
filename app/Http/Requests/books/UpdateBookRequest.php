<?php

namespace App\Http\Requests\books;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        return [
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'isbn' => 'required|string|size:13|unique:books,isbn,' . $this->route('book'),
            'publication_year' => 'required|digits:4|integer|min:1500|max:' . now()->year,
            'book_status_id' => 'required|exists:book_statuses,id',
            'image_book' => 'nullable|image|mimes:jpeg,png,jpg,svg,heic,webp|max:10240',
            'image' => '',
        ];
    }

    public function failedValidation($validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation errors',
            'success' => false,
            'data' => $validator->errors(),
        ], 422));
    }
}
