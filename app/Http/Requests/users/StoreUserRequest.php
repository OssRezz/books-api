<?php

namespace App\Http\Requests\users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'library_id' => 'required|string|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name', // o roles,id si usas ID
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
