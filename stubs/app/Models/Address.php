<?php

namespace App\Models;

use App\Enums\AddressBelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'line_1',
        'line_2',
        'postal_code',
        'country_id',
        'region_id',
        'city_id',
        'landmark',
        'lat',
        'long',
        'belongs_to',
        'applicable_for_shipping'
    ];

    protected $casts = [
        'belongs_to'=> AddressBelongsTo::class,
        'applicable_for_shipping' => 'boolean'
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Country::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Region::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(\App\Models\City::class);
    }

}
