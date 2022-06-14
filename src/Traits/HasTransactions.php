<?php

namespace Sajjadmgd\Zarinpal\Traits;

use Sajjadmgd\Zarinpal\Models\Transaction;

trait HasTransactions
{
  public function transactions()
  {
    return $this->hasMany(Transaction::class, 'user');
  }
}
