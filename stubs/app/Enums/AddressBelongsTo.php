<?php

namespace App\Enums;

enum AddressBelongsTo: string
{

    case CUSTOMER = 'Customer';

    //case USER = 'User';

    public static function getAddressParentModelClass(AddressBelongsTo $belongsTo): string
    {
        return match ($belongsTo) {
            self::CUSTOMER => 'App\Models\Customer',
            //self::USER => 'App\Models\User',
        };
    }

}
