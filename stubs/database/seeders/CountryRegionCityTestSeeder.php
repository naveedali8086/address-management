<?php

namespace seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Throwable;

class CountryRegionCityTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $countries = [
                [
                    'name' => 'United States',
                    'cca2' => 'US',
                    'cca3' => 'USA',
                    'dialing_code' => '+1',
                ],
                [
                    'name' => 'Canada',
                    'cca2' => 'CA',
                    'cca3' => 'CAN',
                    'dialing_code' => '+1',
                ],
                [
                    'name' => 'Pakistan',
                    'cca2' => 'PK',
                    'cca3' => 'PAK',
                    'dialing_code' => '+92',
                ],
            ];

            $states = [
                [
                    'name' => 'Arizona',
                    'country_name' => 'United States',
                    'cities' => [
                        ['name' => 'Phoenix'],
                        ['name' => 'Tucson']
                    ]
                ],
                [
                    'name' => 'California',
                    'country_name' => 'United States',
                    'cities' => [
                        ['name' => 'San Diego'],
                        ['name' => 'Los Angeles'],
                    ]
                ],
                [
                    'name' => 'Alberta',
                    'country_name' => 'Canada',
                    'cities' => [
                        ['name' => 'Calgary'],
                        ['name' => 'Edmonton'],
                    ]
                ],
                [
                    'name' => 'Ontario',
                    'country_name' => 'Canada',
                    'cities' => [
                        ['name' => 'Toronto'],
                        ['name' => 'Ottawa'],
                    ]
                ],
                [
                    'name' => 'Sindh',
                    'country_name' => 'Pakistan',
                    'cities' => [
                        ['name' => 'Karachi'],
                        ['name' => 'Hyderabad'],
                    ]
                ],
                [
                    'name' => 'Punjab',
                    'country_name' => 'Pakistan',
                    'cities' => [
                        ['name' => 'Lahore'],
                        ['name' => 'Multan'],
                    ]
                ],
            ];

            DB::beginTransaction();

            Country::query()->insert($countries);

            foreach ($states as $state) {

                $countryId = Country::query()
                    ->where('name', $state['country_name'])
                    ->value('id');

                $region = Region::query()->create([
                    'name' => $state['name'],
                    'country_id' => $countryId
                ]);

                foreach ($state['cities'] as $city) {
                    City::query()->create([
                        'name' => $city['name'],
                        'country_id' => $countryId,
                        'region_id' => $region->id
                    ]);
                }

            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            echo "Error while running " . get_class($this) . "\n\n";
            echo $e->getMessage();
            echo "\n\n";
        }
    }
}
