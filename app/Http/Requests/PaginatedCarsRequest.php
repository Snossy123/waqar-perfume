<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaginatedCarsRequest extends FormRequest
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
            'brand_id' => 'nullable|integer|exists:brands,id',
            'brand' => 'nullable|string',
            'car_model_id' => 'nullable|integer|exists:car_models,id',
            'model' => 'nullable|string',
            'model_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'refurbishment_status' => 'nullable|string|in:' . implode(',', array_map(fn($status) => $status->value, \App\Enums\RefurbishmentStatus::cases())),
            'color' => 'nullable|string',
            'location' => 'nullable|string',

            'price_range' => 'nullable|array|size:2',
            'price_range.0' => 'nullable|numeric',
            'price_range.1' => 'nullable|numeric',
            'price_from' => 'nullable|numeric',
            'price_to' => 'nullable|numeric',


            'years_model' => "nullable|array|size:2",
            'years_model.0' => "nullable|integer|min:1900|max:" . (date('Y') + 1),
            'years_model.1' => "nullable|integer|min:1900|max:" . (date('Y') + 1),

            'transmission_type_ids' => 'nullable|array',
            'transmission_type_ids.*' => 'integer|exists:transmission_types,id',

            'kilometers' => 'nullable|numeric',
            'km_from' => 'nullable|numeric',
            'km_to' => 'nullable|numeric',

            'installment' => 'nullable|array|size:2',
            'installment.0' => 'nullable|numeric',
            'installment.1' => 'nullable|numeric',

            'fuel_economy' => 'nullable|array',
            'fuel_economy.min' => 'nullable|numeric',
            'fuel_economy.max' => 'nullable|numeric',

            'transmission' => 'nullable|string',
            'transmission_type_id' => 'nullable|integer|exists:transmission_types,id',
            'body_style_id' => 'nullable|integer|exists:body_styles,id',
            'search' => 'nullable|string|max:255',
            
            'car_types_ids'=>'nullable|array',
            'car_types_ids.*'=>'integer|exists:types,id',

            'brand_ids' => 'nullable|array',
            'brand_ids.*' => 'integer|exists:brands,id',
            'body_style_ids' => 'nullable|array',
            'body_style_ids.*' => 'integer|exists:body_styles,id',
            'vehicle_status' => 'nullable|string',
            'condition' => 'nullable|string',
            'engine_capacity_cc' => 'nullable|array',
            'engine_capacity_cc.0' => 'nullable|numeric',
            'engine_capacity_cc.1' => 'nullable|numeric',

            'down_payment' => 'nullable|numeric|min:0|max:99999999.99',
            'down_payment_range' => 'nullable|array|size:2',
            'down_payment_range.0' => 'nullable|numeric',
            'down_payment_range.1' => 'nullable|numeric',

            'down_payment_from' => 'nullable|numeric',
            'down_payment_to' => 'nullable|numeric'
        ];
    }

    public function messages(): array
    {
        return [
            'per_page.max' => 'Maximum 100 items per page allowed.',
            'max_price.gt' => 'Maximum price must be greater than minimum price.',
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
