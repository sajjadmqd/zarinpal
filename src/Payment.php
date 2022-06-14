<?php

namespace Sajjadmgd\Zarinpal;

use Illuminate\Database\Eloquent\Model;
use Sajjadmgd\Zarinpal\Classes\Zarinpal;
use Sajjadmgd\Zarinpal\Models\Transaction;

class Payment
{
    const TransctionStatuses = [
        'پرداخت نشده' => 'not-deposited',
        'پرداخت شده' => 'deposited',
        'لغو شده توسط کاربر' => 'canceled',
        'ناموفق' => 'unsuccessful'
    ];

    public function __construct()
    {
        $this->zarinpal = new Zarinpal();
    }

    public function create(int $value, int $user_id, Model $transactionable, string $description)
    {
        $merchantID = config('merchant_id');
        $callbackURL = route('transactions.verify');
        $now = now();
        $res = $this->zarinpal->request($merchantID, $value, $callbackURL, $description);
        $transaction = Transaction::new([
            'amount' => $value,
            'user_id' => $user_id,
            'status' => 'not-deposited',
            'description' => $description,
            'authority' => $res->authority,
            'start_pay' => $res->startPayUrl,
            'expire_in' => $now->addMinutes(30)
        ]);

        $transactionable->transactions()->save($transaction);

        return $transaction->id;
    }

    public function cancel(string $authority)
    {
        $res = Transaction::firstWhere('authority', $authority)->update([
            'status' => 'canceled',
            'canceled_at' => now()
        ]);

        return $res;
    }

    public function pay(int $id)
    {
        $transaction = Transaction::find($id);
        return redirect($transaction->start_pay);
    }
}
