<?php


use App\Enums\AddressBelongsTo;
use App\Models\Address;
use App\Models\Customer;
use App\Models\User;
use Database\Seeders\CountryRegionCityTestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    // This trait resets the database after each test.
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(
            User::factory()->create()
        );

        $this->seed(CountryRegionCityTestSeeder::class);
    }

    /** @test */
    public function it_can_create_an_address()
    {
        $address = Address::factory()->make()->toArray();
        $address['belongs_to_id'] = Customer::factory()->create()->value('id');

        $response = $this->postJson('/api/addresses', $address);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['data' => $this->getAddressAttributes()]);

        $this->assertEquals($address['line_1'], $response->json('data.line_1'));
        $this->assertEquals($address['country_id'], $response->json('data.country.id'));
        $this->assertEquals($address['region_id'], $response->json('data.region.id'));
        $this->assertEquals($address['city_id'], $response->json('data.city.id'));
    }

    /** @test */
    public function it_cannot_create_an_address_with_invalid_data()
    {
        $address = Address::factory()->make()->toArray();
        $address['belongs_to_id'] = Customer::factory()->create()->value('id');
        $address['line_1'] = ''; // emptying required field to create validation errors

        $response = $this->postJson('/api/addresses', $address);

        $response
            ->assertStatus(422) // Validation error
            ->assertJsonValidationErrors(['line_1']);
    }

    /** @test */
    public function it_can_update_an_address()
    {
        $address = $this->createAddressWithParent();
        $address->line_1 = fake()->streetAddress();

        $address = $address->toArray();
        $response = $this->putJson("/api/addresses/{$address['id']}", $address);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['data' => $this->getAddressAttributes()]);

        // unset "belongs_to_id" as it does not exist in DB and was only required while sending a request
        unset($address['belongs_to_id']);
        $this->assertDatabaseHas('addresses', $address);
    }

    /** @test */
    public function it_cannot_update_an_address_with_invalid_data()
    {
        $address = $this->createAddressWithParent()->toArray();
        $address['line_1'] = ''; // emptying required field to create validation errors

        $response = $this->putJson("/api/addresses/{$address['id']}", $address);

        $response
            ->assertStatus(422) // Validation error
            ->assertJsonValidationErrors(['line_1']);
    }

    /** @test */
    public function it_can_soft_delete_an_address()
    {
        $address = $this->createAddressWithParent();

        $response = $this->deleteJson("/api/addresses/$address->id");

        $response->assertStatus(204);

        $this->assertSoftDeleted('addresses', ['id' => $address->id]);
    }

    /** @test */
    public function it_can_get_a_single_address()
    {
        $address = $this->createAddressWithParent()->toArray();

        $response = $this->getJson("/api/addresses/{$address['id']}");

        $response->assertStatus(200);

        // removing attributes from $address and $responseAddress so that both the
        // assertEquals can pass
        $address = Arr::except($address, ['country_id', 'region_id', 'city_id']);
        $responseAddress = Arr::except($response->json('data'), ['country', 'region', 'city']);

        $this->assertEquals($address, $responseAddress);
    }

    /** @test */
    public function it_can_get_all_addresses()
    {
        $this->createAddressWithParent(10);

        $response = $this->getJson('/api/addresses');

        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    // '*' means there can be multiple items in the 'data' array.
                    '*' => $this->getAddressAttributes()
                ]
            ]);
    }

    private function getAddressAttributes(array $except = []): array
    {
        $addCustomerAttrs = [
            'id',
            'line_1',
            'line_2',
            'postal_code',
            'country',
            'region',
            'city',
            'landmark',
            'lat',
            'long',
            'belongs_to',
            'belongs_to_id',
            'applicable_for_shipping',
            'created_at',
            'updated_at'
        ];

        return array_diff($addCustomerAttrs, $except);
    }

    /**
     * It creates address's parent based on its "belongs_to" attribute's value
     *
     * @param $addressCount
     * @return mixed
     */
    private function createAddressWithParent($addressCount = 1)
    {
        $address = Address::factory()->count($addressCount)->make();

        $address->each(function (Address $address) {

            // Getting address's parent model fully qualified class name
            $parentModelClass = AddressBelongsTo::getAddressParentModelClass($address->belongs_to);

            // getting address's parent model
            $parentModel = $parentModelClass::factory()->create();

            $parentModel->addresses()->save($address);

            // hiding following two attributes because they will be automatically
            //  be assigned at server end by Laravel framework
            $address->makeHidden(['addressable_id', 'addressable_type']);

            // an address must send "belongs_to_id" when creating/updating an address
            $address->belongs_to_id = $parentModel->id;

        });
        return $addressCount === 1 ? $address->first() : $address;
    }
}
