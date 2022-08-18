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
        'start_pay',
        'user_id',
        'transactionable_id',
        'transactionable_type',
        'canceled_at',
        'paid_at',
        'expire_in'
    ];

    protected $dates = [
        'canceled_at',
        'paid_at',
        'expire_in'
    ];

    protected $appends = [
        'failed'
    ];

    public function getFailedAttribute()
    {
        return $this->status == 'unsuccessful' or $this->status == 'canceled';
    }

    public function transactionable()
    {
        return $this->morphTo('transactionable');
    }

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    protected static function newFactory()
    {
        return \Sajjadmgd\Zarinpal\Database\Factories\TransactionFactory::new();
    }
}
