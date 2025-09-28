<?php

namespace App\Http\Requests;

use App\Enums\RefurbishmentStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class UpdateCarRequest extends FormRequest
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
            // Identification
            'brand' => 'nullable|integer',
            'model' => 'nullable|integer',
            'model_year' => 'nullable|numeric|min:1900|max:' . (date('Y') + 1),

            // Vehicle Specs
            'body_style' => 'nullable|integer',
            'type' => 'nullable|integer',
            'transmission_type' => 'nullable|integer',
            'drive_type' => 'nullable|integer',

            // Appearance
            'color_ar' => 'nullable|string|max:50',
            'color_en' => 'nullable|string|max:50',
            'license_expire_date' => 'nullable|date|after:today',

            // Dimensions
            'size_id' => 'nullable|integer',
            'length' => 'nullable|numeric|min:0|max:99999',
            'width' => 'nullable|numeric|min:0|max:99999',
            'height' => 'nullable|numeric|min:0|max:99999',

            // Engine & Performance
            'fuel_economy_id' => 'nullable|integer',
            'min_fuel_economy' => 'nullable|numeric|min:0|max:999',
            'max_fuel_economy' => 'nullable|numeric|min:0|max:999',
            'engine_type' => 'nullable|integer',
            'engine_capacity' => 'nullable|numeric|min:0|max:99999',
            'horsepower_id' => 'nullable|integer',
            'min_horse_power' => 'nullable|numeric|min:0|max:1000',
            'max_horse_power' => 'nullable|numeric|min:0|max:1000',
            'mileage' => 'nullable|numeric|min:0|max:9999999',

            // Status
            'vehicle_status' => 'nullable|string|max:50',
            'refurbishment_status' => 'nullable|string|max:50',

            // Pricing
            'price' => 'required|numeric|min:0|max:9999999999.99',
            'discount' => 'nullable|numeric|min:0|max:999999.99',
            'monthly_installment' => 'nullable|numeric|min:0|max:99999999.99',
            'down_payment' => 'nullable|numeric|min:0|max:99999999.99',

            // Classification
            'trim' => 'nullable|integer',

            // Flags
            'flags' => 'nullable|array',
            'flags.*.id' => 'nullable|integer|exists:flags,id',
            'flags.*.name_ar' => 'nullable|string|max:100',
            'flags.*.name_en' => 'nullable|string|max:100',
            'flags.*.image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',

            // Features (multi-dimensional)
            'features' => 'nullable|array',
            'features.*' => 'nullable|array',
            'features.*.*.id' => 'nullable|integer|exists:features,id',
            'features.*.*.name' => 'required_with:features.*.*.label|string|max:100',
            'features.*.*.label_en' => 'required_with:features.*.*.name|string|max:100',
            'features.*.*.label_ar' => 'required_with:features.*.*.name|string|max:100',
            'features.*.*.value_en' => 'required_with:features.*.*.name|string|max:100',
            'features.*.*.value_ar' => 'required_with:features.*.*.name|string|max:100',

            // Conditions (multi-dimensional)
            'conditions' => 'nullable|array',
            'conditions.*' => 'nullable|array',
            'conditions.*.*.id' => 'nullable|integer',
            'conditions.*.*.name' => 'required_with:conditions.*.*.part|string|max:100',
            'conditions.*.*.part_ar' => 'required_with:conditions.*.*.name|string|max:100',
            'conditions.*.*.part_en' => 'required_with:conditions.*.*.name|string|max:100',
            'conditions.*.*.description_ar' => 'nullable|string|max:255',
            'conditions.*.*.description_en' => 'nullable|string|max:255',
            'conditions.*.*.image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',

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
