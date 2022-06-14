<?php

namespace Sajjadmgd\Zarinpal;

class Helpers
{
    const TransctionStatuses = [
        'پرداخت نشده' => 'not-deposited',
        'پرداخت شده' => 'deposited',
        'لغو شده توسط کاربر' => 'user_canceled',
        'ناموفق' => 'unsuccessful'
    ];
}
