<?php

namespace Module\Billing\Entities;

use Illuminate\Database\Eloquent\Model;

class BalanceTransaction extends Model
{
    /**
     * @var string
     */
    protected $table = 'balance_transactions';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return static
     */
    public static function createInflow($params)
    {
        $params = array_merge($params, ['inflow' => true]);

        return static::create($params);
    }

    public function isInflow()
    {
        return (boolean)$this->inflow;
    }
}