<?php

namespace Database\Factories;

use App\Enums\AddressBelongsTo;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $countries = Country::select('id')->pluck('id')->toArray();
        $selectedCountryId = \Database\Factories\fake()->randomElement($countries);

        $regions = Region::select('id')->where('country_id', $selectedCountryId)->pluck('id')->toArray();
        $selectedRegionId = \Database\Factories\fake()->randomElement($regions);

        $cities = City::select('id')->where('region_id', $selectedRegionId)->pluck('id')->toArray();
        $selectedCityId = \Database\Factories\fake()->randomElement($cities);

        $definition = [
            'line_1' => \Database\Factories\fake()->streetAddress(),
            'line_2' => \Database\Factories\fake()->randomElement([\Database\Factories\fake()->streetAddress(), null]),
            'postal_code' => \Database\Factories\fake()->randomElement([\Database\Factories\fake()->postcode(), null]),
            'country_id' => $selectedCountryId,
            'region_id' => $selectedRegionId,
            'city_id' => $selectedCityId,
            'landmark' => \Database\Factories\fake()->randomElement([\Database\Factories\fake()->word(), null]),
            'lat' => \Database\Factories\fake()->randomElement([\Database\Factories\fake()->latitude(), null]),
            'long' => \Database\Factories\fake()->randomElement([\Database\Factories\fake()->longitude(), null]),
            'belongs_to' => \Database\Factories\fake()->randomElement(AddressBelongsTo::cases()),
            'applicable_for_shipping' => \Database\Factories\fake()->randomElement([0, 1])
        ];

        // set longitude if latitude is set
        if (isset($defination['lat']) && !isset($defination['long'])) {
            $definition['long'] = \Database\Factories\fake()->longitude();
        } else if (isset($defination['long']) && !isset($defination['lat'])) {
            // set latitude if longitude is set
            $definition['lat'] = \Database\Factories\fake()->latitude();
        }

        return $definition;
    }
}
