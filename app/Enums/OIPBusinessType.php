<?php

namespace App\Enums;

enum OIPBusinessType: string
{
    case NEW_ACCOUNT = 'new_account';
    case EXISTING_ACCOUNT_NEW_BUSINESS = 'existing_account_new_business';
    case VALUE_ADD_SALE_EXISTING_BUSINESS = 'value_add_sale_existing_business';
}
