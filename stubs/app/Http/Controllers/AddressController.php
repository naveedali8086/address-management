<?php

namespace App\Http\Controllers;

use App\Enums\AddressBelongsTo;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressCollection;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Support\Arr;

class AddressController extends Controller
{

    /**
     * Get a list of Address.
     */
    public function index()
    {
        $address = Address::with(['country', 'region', 'city'])
            ->paginate(10);

        return new AddressCollection($address);
    }

    /**
     * Store an Address in storage.
     */
    public function store(StoreAddressRequest $request)
    {
        // Getting address's parent model fully qualified class name
        $parentModelClass = AddressBelongsTo::getAddressParentModelClass(
            $request->enum('belongs_to', AddressBelongsTo::class)
        );

        // getting address's parent model
        $parentModel = $parentModelClass::findOrFail($request->input('belongs_to_id'));

        $address = $parentModel->addresses()->create(
            Arr::except($request->validated(), ['belongs_to_id'])
        );

        if ($address) {
            $address->load(['country', 'region', 'city']);
            return (new AddressResource($address))
                ->response()
                ->setStatusCode(201);
        } else {
            abort(500, 'Failed to store the address');
        }
    }

    /**
     * Get the specified Address.
     */
    public function show(Address $address)
    {
        $address->load(['country', 'region', 'city']);
        return new AddressResource($address);
    }

    /**
     * Update the specified Address in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        // Getting address's parent model fully qualified class name
        $parentModelClass = AddressBelongsTo::getAddressParentModelClass(
            $request->enum('belongs_to', AddressBelongsTo::class)
        );

        // getting address's parent model
        $parentModel = $parentModelClass::findOrFail($request->input('belongs_to_id'));

        $updated = $parentModel->addresses()->update(
            Arr::except($request->validated(), ['belongs_to_id'])
        );

        if ($updated) {
            $address->load(['country', 'region', 'city']);
            return new AddressResource($address);
        } else {
            abort(500, 'Failed to update the address');
        }
    }

    /**
     * Remove the specified Address from storage.
     */
    public function destroy(Address $address)
    {
        if ($address->delete()) {
            return response(null, 204);
        } else {
            abort(500, 'Failed to delete the address');
        }
    }

}
