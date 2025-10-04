<?php

namespace App\Http\Requests;

use App\Enums\RefurbishmentStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class UpdateProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:9999999999.99',
            'discounted_price' => 'nullable|numeric|min:0|max:9999999999.99',
            'stars' => 'nullable|integer|min:0|max:5',
            'offer_end_date' => 'nullable|date|after:today',
            'stock' => 'nullable|integer|min:0|max:99999',
            'category_id' => 'required|exists:categories,id',

            // Images (optional multiple)
            'images' => 'nullable|array',
            'images.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'nullable|integer'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        if(request()->expectsJson()){
            throw new HttpResponseException (
                response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422));
        }

        throw new HttpResponseException(
            redirect()
            ->back()
            ->withErrors($validator)
            ->withInput()
        );
    }
}
