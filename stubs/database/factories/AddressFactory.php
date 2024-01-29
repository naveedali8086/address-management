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
        $selectedCountryId = fake()->randomElement($countries);

        $regions = Region::select('id')->where('country_id', $selectedCountryId)->pluck('id')->toArray();
        $selectedRegionId = fake()->randomElement($regions);

        $cities = City::select('id')->where('region_id', $selectedRegionId)->pluck('id')->toArray();
        $selectedCityId = fake()->randomElement($cities);

        $definition = [
            'line_1' => fake()->streetAddress(),
            'line_2' => fake()->randomElement([fake()->streetAddress(), null]),
            'postal_code' => fake()->randomElement([fake()->postcode(), null]),
            'country_id' => $selectedCountryId,
            'region_id' => $selectedRegionId,
            'city_id' => $selectedCityId,
            'landmark' => fake()->randomElement([fake()->word(), null]),
            'lat' => fake()->randomElement([fake()->latitude(), null]),
            'long' => fake()->randomElement([fake()->longitude(), null]),
            'belongs_to' => fake()->randomElement(AddressBelongsTo::cases()),
            'applicable_for_shipping' => fake()->randomElement([0, 1])
        ];

        // set longitude if latitude is set
        if (isset($defination['lat']) && !isset($defination['long'])) {
            $definition['long'] = fake()->longitude();
        } else if (isset($defination['long']) && !isset($defination['lat'])) {
            // set latitude if longitude is set
            $definition['lat'] = fake()->latitude();
        }

        return $definition;
    }
}
