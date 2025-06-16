<?php

namespace App\Http\Requests\borrowing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReturnBooksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_ids' => 'required|array|min:1',
            'book_ids.*' => 'exists:books,id',
        ];
    }

    public function messages(): array
    {
        return [
            'book_ids.required' => 'You must provide at least one book to return.',
            'book_ids.array' => 'The book IDs must be in an array format.',
            'book_ids.*.exists' => 'One or more selected books do not exist.',
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
