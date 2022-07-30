<?php

return [
    'merchant_id' => env('MERCHANT_ID'),
    'verify_action' => '' // e.g. '/App/Http/Controllers/PaymentController@verify' that gets (Request $request) as params
];
