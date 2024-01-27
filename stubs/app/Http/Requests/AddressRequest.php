<?php

namespace App\Http\Requests;

use App\Enums\AddressBelongsTo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddressRequest extends FormRequest
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
            'line_1' => 'required|max:255',
            'line_2' => 'sometimes|nullable|max:255',
            'postal_code' => 'sometimes|nullable|max:255',
            'country_id' => 'required|exists:countries,id',
            'region_id' => 'required|exists:regions,id',
            'city_id' => 'required|exists:cities,id',
            'landmark' => 'sometimes|nullable|max:255',
            'lat' => 'sometimes|nullable|max:255',
            'long' => 'sometimes|nullable|max:255',
            'belongs_to' => ['required', Rule::enum(AddressBelongsTo::class)],
            'belongs_to_id' => 'required',
            'applicable_for_shipping' => 'sometimes|nullable|boolean'
        ];
    }
}
