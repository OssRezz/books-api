<?php

namespace App\Http\Requests\borrowing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BorrowBooksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_ids' => 'required|array|min:1|max:3',
            'book_ids.*' => 'exists:books,id',
        ];
    }

    public function messages(): array
    {
        return [
            'book_ids.required' => 'You must provide at least one book to borrow.',
            'book_ids.array' => 'The book IDs must be in an array format.',
            'book_ids.min' => 'At least one book must be selected.',
            'book_ids.max' => 'You cannot borrow more than 3 books at a time.',
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
