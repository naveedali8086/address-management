<?php

namespace App\Http\Requests;

use App\Enums\AddressBelongsTo;
use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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

    public function after()
    {
        return [
            function (Validator $validator) {

                $countryId = $validator->getValue('country_id');
                $regionId = $validator->getValue('region_id');
                $cityId = $validator->getValue('city_id');

                if ($countryId && $regionId && $cityId) {

                    $city = City::with(['country', 'region'])->find($cityId);

                    // Make sure that the selected city has correct path till country
                    if ($city->country_id !== $countryId ||
                        $city->region_id !== $regionId ||
                        $city->id !== $cityId) {

                        $validator->errors()->add(
                            'city_id',
                            'Selected country, region or city is invalid'
                        );

                    }
                }
            }
        ];
    }
}
