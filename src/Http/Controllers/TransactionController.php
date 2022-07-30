<?php

namespace Sajjadmgd\Zarinpal\Http\Controllers;

use Illuminate\Http\Request;
use Sajjadmgd\Zarinpal\Classes\Zarinpal;
use Sajjadmgd\Zarinpal\Models\Transaction;

class TransactionController extends Controller
{
    public function verify(Request $request)
    {
        $zarinpal = new Zarinpal();
        $merchantID = config('zarinpal.merchant_id');
        $transaction = Transaction::firstWhere(['authority' => $request->Authority]);
        $res = $zarinpal->verify($merchantID, $transaction->authority, $transaction->amount);

        if ($res->status == 100) {
            $transaction->update([
                'status' => 'deposited',
                'ref_id' => $res->refID,
                'payed_at' => now()
            ]);
        } else if ($res->status == 101) {
            $transaction->update([
                'status' => 'deposited',
                'ref_id' => $res->refID
            ]);
        }

        return redirect()->action(config('zarinpal.verify_action'), ['res' => $res]);
    }
}
