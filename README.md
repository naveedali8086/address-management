## Introduction

This package provides a minimal address management / CRUD scaffolding, in which an `Address` entity morphTo
any entity that is addressable i.e. Customer, Employee, User or any other entity is addressable. So you do not need 
to redefine the address related fields in parent entity (that is addressable) or make changes in a controller, 
formRequests and all other places to accommodate address related fields. All you have to do is just add
a new case in `App\Enums\AddressBelongsTo` as well as in `getAddressParentModelClass()` method of this enum that represent
your model/entity, and you are good to go. And yes, once you add your new parent model that would have addresses, do not
forget to add `addresses()` relationship method in that model.

It publishes controllers, models, formRequests, resources, migrations, factories, test cases for Country, 
Region, City and Address resources to your application that can be easily customized based on your own application's needs.

Note: To populate you app DB with real countries, regions and cities, please download from this repo

### Entities

#### Country
| Attribute    | Type      | Required                     | Description                     |
|--------------|-----------|------------------------------|---------------------------------|
| id           | bigInt    | No <br>(handled by Laravel)  |                                 |
| name         | string    | Yes                          |                                 |
| cca2         | string    | Yes                          | 2-digit alphabetic country code |
| cca3         | string    | Yes                          | 3-digit alphabetic country code |
| dialing_code | string    | Yes                          |                                 |
| created_at   | timestamp | No <br>(handled by Laravel)  |                                 |
| updated_at   | timestamp | No <br>(handled by Laravel)  |                                 |
| deleted_at   | timestamp | No <br>(handled by Laravel)  |                                 |

#### Region
| Attribute    | Type      | Required                     | Description                              |
|--------------|-----------|------------------------------|------------------------------------------|
| id           | bigInt    | No <br>(handled by Laravel)  |                                          |
| name         | string    | Yes                          | The state, province or territory name    |
| country_id   | bigInt    | Yes                          | Country with which the region belongs to |
| created_at   | timestamp | No <br>(handled by Laravel)  |                                          |
| updated_at   | timestamp | No <br>(handled by Laravel)  |                                          |
| deleted_at   | timestamp | No <br>(handled by Laravel)  |                                          |

#### City
| Attribute  | Type      | Required                     | Description                                                         |
|------------|-----------|------------------------------|---------------------------------------------------------------------|
| id         | bigInt    | No <br>(handled by Laravel)  |                                                                     |
| name       | string    | Yes                          | The name of the city                                                |
| country_id | bigInt    | Yes                          | Country with which the city belongs to                              |
| region_id  | bigInt    | Yes                          | Region (state, province or territory with which the city belongs to |
| created_at | timestamp | No <br>(handled by Laravel)  |                                                                     |
| updated_at | timestamp | No <br>(handled by Laravel)  |                                                                     |
| deleted_at | timestamp | No <br>(handled by Laravel)  |                                                                     |

#### Address
| Attribute               | Type      | Required                    | Description                                                                                                                                                                                                                                                               |
|-------------------------|-----------|-----------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| id                      | bigInt    | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                           |
| line_1                  | string    | Yes                         |                                                                                                                                                                                                                                                                           |
| line_2                  | string    | No                          |                                                                                                                                                                                                                                                                           |
| postal_code             | string    | No                          |                                                                                                                                                                                                                                                                           |
| country_id              | bigInt    | Yes                         |                                                                                                                                                                                                                                                                           |
| region_id               | bigInt    | Yes                         |                                                                                                                                                                                                                                                                           |
| city_id                 | bigInt    | Yes                         |                                                                                                                                                                                                                                                                           |
| landmark                | string    | No                          |                                                                                                                                                                                                                                                                           |
| lat                     | string    | No                          |                                                                                                                                                                                                                                                                           |
| long                    | string    | No                          |                                                                                                                                                                                                                                                                           |
| addressable_id          | bigInt    | Yes                         | The ID of the parent model this address belongs to. i.e. the parent model may a Customer, an Employee and so on                                                                                                                                                           |
| addressable_type        | string    | Yes                         | The string identifier of the parent model this address belongs to. i.e. It may a App\Models\Customer, App\Models\Employee and so on <br/> (note: as  per laravel docs, this string identifier is customizable and for this reason a belongs_to attribute was added below) |
| belongs_to              | string    | Yes                         | The string identifier of the parent model this address belongs to. This string identifier would be unique_key per model                                                                                                                                                   |
| applicable_for_shipping | boolean   | No                          |                                                                                                                                                                                                                                                                           |
| created_at              | timestamp | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                           |
| updated_at              | timestamp | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                           |
| deleted_at              | timestamp | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                           |


## How to install
Step 1:
```
composer require naveedali8086/address-management 
```
<br>
Step 2:
<br>
Now publish the scaffolding provided by all these packages via:

```
php artisan address-crud:install
```

Step 3:
<br>

```
php artisan migrate
```

<br>
Step 4:
<br>

Define country, region, cities and address crud routes in `api.php` or `web.php` routes files as per your needs.
```
api.php:
--------
Route::apiResource('countries', CountryController::class)
Route::apiResource('regions', RegionController::class)
Route::apiResource('cities', CityController::class)
Route::apiResource('addresses', AddressController::class)

web.php:
--------
Route::resource('countries', CountryController::class)
Route::resource('regions', RegionController::class)
Route::resource('cities', CityController::class)
Route::resource('addresses', AddressController::class)

Note: you may be need to append except() or only() functions in above routes in web.php to only add routes that provide 
CRUD's backend functionality
```

### Run the tests

```
php artisan test tests/Feature/CountryControllerTest.php
php artisan test tests/Feature/RegionControllerTest.php
php artisan test tests/Feature/CityControllerTest.php
php artisan test tests/Feature/AddressControllerTest.php
```
P.S. Or run all the tests in one go using <br> `php artisan test tests/Feature/CountryControllerTest.php tests/Feature/RegionControllerTest.php tests/Feature/CityControllerTest.php tests/Feature/AddressControllerTest.php` <br>
and so.

### Questions?
In case there is anything unclear feel free to reach out to me at naveedali8086@gmail.com

### Authors

[**Naveed Ali**](https://github.com/naveedali8086)

### License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details


