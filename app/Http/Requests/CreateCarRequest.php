<?php

namespace App\Http\Requests;

use App\Enums\RefurbishmentStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class CreateCarRequest extends FormRequest
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
            // Car identification
            'brand' => 'required',
            'model' => 'required',
            'model_year' => 'required|numeric|min:1900|max:' . (date('Y') + 1),
            // Vehicle specifications
            'body_style' => 'nullable',
            'type' => 'nullable',
            'transmission_type' => 'nullable',
            'drive_type' => 'nullable',
            // Physical attributes
            'color_ar' => 'nullable|string|max:50',
            'color_en' => 'nullable|string|max:50',
            // License Expiry Date
            'license_expire_date' => 'nullable|date|after:today',
            // Dimensions
            'length' => 'nullable|numeric|min:0|max:99999',
            'width' => 'nullable|numeric|min:0|max:99999',
            'height' => 'nullable|numeric|min:0|max:99999',
            // Fuel Economy
            'min_fuel_economy' => 'nullable|numeric|min:0|max:999',
            'max_fuel_economy' => 'nullable|numeric|min:0|max:999',
            // Engine type and capacity
            'engine_type' => 'nullable',
            'engine_capacity' => 'nullable|numeric|min:0|max:99999',
            // Power
            'min_horse_power' => 'nullable|numeric|min:0|max:1000',
            'max_horse_power' => 'nullable|numeric|min:0|max:1000',
            // Performance & condition
            'mileage' => 'nullable|numeric|min:0|max:9999999',
            'vehicle_status' => 'nullable',
            'refurbishment_status' => 'nullable',
            // Pricing
            'price' => 'required|numeric|min:0|max:9999999999.99',
            'discount' => 'nullable|numeric|min:0|max:999999.99',
            'monthly_installment' => 'nullable|numeric|min:0|max:99999999.99',
            'down_payment' => 'nullable|numeric|min:0|max:99999999.99',
            // Classification
            'trim' => 'nullable',
            // Images upload
            'images' => 'nullable|array',
            //            'images.*' => 'file|mimes:jpeg,png,jpg,gif,svg',
            // Flags, Features, and Conditions (Array)
            'flags' => 'nullable|array',
            'flags.*.name_ar' => 'nullable|string|max:100',
            'flags.*.name_en' => 'nullable|string|max:100',
            'flags.*.image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // Features (Nested for dynamic input)
            'features' => 'nullable|array',
            'features.*.name' => 'nullable|string|max:100',
            'features.*.label' => 'nullable|array',
            'features.*.label.en' => 'nullable|string|max:100',
            'features.*.label.ar' => 'nullable|string|max:100',
            'features.*.value' => 'nullable|array',
            'features.*.value.en' => 'nullable|string|max:100',
            'features.*.value.ar' => 'nullable|string|max:100',
            'inputConditions' => 'nullable|array',
            'conditions' => 'nullable|array',
            'conditions.*.name' => 'nullable|string|max:100',  // For condition input
            'conditions.*.part' => 'nullable|string|max:100',  // For part input
            'conditions.*.description' => 'nullable|string|max:255',  // For description input
            'conditions.*.image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',  // For value input
        ];
    }

    protected function failedValidation(Validator $validator)
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
