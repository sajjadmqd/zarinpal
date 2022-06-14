<?php

namespace Sajjadmgd\Zarinpal\Http\Controllers;

use Illuminate\Http\Request;
use Sajjadmgd\Zarinpal\Models\Transaction;

class PostController extends Controller
{
    public function verify(Request $request)
    {
        $merchantID = config('merchant_id');
        $transaction = Transaction::firstWhere(['authority' => $request->Authority]);
        $res = $this->zarinpal->verify($merchantID, $transaction->authority, $transaction->amount);

        if ($res->status == 100) {
            $transaction->update([
                'status' => 'deposited',
                'ref_id' => $res->refID,
                'payed_at' => now()
            ]);
        }

        return $res;
    }
}
