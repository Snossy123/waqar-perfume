<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaginatedProductsRequest extends FormRequest
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
            'category_id' => 'nullable|integer|exists:categories,id',
            
            'price_range' => 'nullable|array|size:2',
            'price_range.0' => 'nullable|numeric',
            'price_range.1' => 'nullable|numeric',
            
            'search' => 'nullable|string|max:255'
        ];
    }


    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if (request()->expectsJson()) {
            // For API requests, return JSON response with validation errors
            throw new HttpResponseException(
                response()->json([
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], 422)
            );
        }

        // For web requests, redirect back with validation errors
        throw new HttpResponseException(
            redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
