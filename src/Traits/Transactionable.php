<?php

namespace Sajjadmgd\Zarinpal\Traits;

use Sajjadmgd\Zarinpal\Models\Transaction;

trait Transactionable
{
  public function transactions()
  {
    return $this->morphMany(Transaction::class, 'transactionable');
  }
}
