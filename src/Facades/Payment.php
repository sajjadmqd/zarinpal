<?php

namespace Sajjadmgd\Zarinpal\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Eloquent\Model;
use Sajjadmgd\Zarinpal\Classes\Zarinpal;
use Sajjadmgd\Zarinpal\Models\Transaction;

class Payment extends Facade
{

    const TransctionStatuses = [
        'پرداخت نشده' => 'not-deposited',
        'پرداخت شده' => 'deposited',
        'لغو شده توسط کاربر' => 'canceled',
        'ناموفق' => 'unsuccessful'
    ];
    
    protected static function getFacadeAccessor()
    {
        return 'payment';
    }

    public static function getTransactions()
    {
        return Transaction::whereNotNull('id');
    }

    public static function create(int $value, int $user_id, Model $transactionable, string $description)
    {
        $zarinpal = new Zarinpal();

        $merchantID = config('zarinpal.merchant_id');
        $callbackURL = route('transactions.verify');
        $now = now();
        $res = $zarinpal->request($merchantID, $value, $callbackURL, $description);
        $transaction = new Transaction([
            'amount' => $value,
            'user_id' => $user_id,
            'status' => 'not-deposited',
            'description' => $description,
            'authority' => $res->authority,
            'start_pay' => $res->startPay,
            'expire_in' => $now->addMinutes(30)
        ]);

        $transactionable->transactions()->save($transaction);

        return $transaction;
    }

    public static function cancel(string $authority)
    {
        $res = Transaction::firstWhere('authority', $authority)->update([
            'status' => 'canceled',
            'canceled_at' => now()
        ]);

        return $res;
    }

    public static function pay(int $id)
    {
        $transaction = Transaction::find($id);
        return redirect($transaction->start_pay);
    }
}
