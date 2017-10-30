<?php

namespace Module\Billing\Entities;

use Illuminate\Database\Eloquent\Model;

class BalanceState extends Model
{
    /**
     * @var string
     */
    protected $table = 'balance_states';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'active_at'
    ];
}