<?php

namespace Sajjadmgd\Zarinpal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'status',
        'description',
        'authority',
        'ref_id',
        'payable_id',
        'payable_type',
        'paid_at',
        'expire_in'
    ];

    protected $guarded = [];

    public function payable()
    {
        return $this->morphTo('payable');
    }

    protected static function newFactory()
    {
        return \Sajjadmgd\Zarinpal\Database\Factories\TransactionFactory::new();
    }
}
