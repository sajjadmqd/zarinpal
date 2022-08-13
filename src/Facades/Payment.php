<?php

namespace Sajjadmgd\Zarinpal\Facades;

use Illuminate\Support\Facades\Facade;
use Sajjadmgd\Zarinpal\Models\Transaction;

class Payment extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'payment';
    }

    public static function getTransactions()
    {
        return Transaction::whereNotNull('id');
    }
}
