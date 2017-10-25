<?php

namespace Module\Billing\Entities;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_wallets';

    /**
     * @return float
     */
    public function totalBalance()
    {
        return $this->balance;
    }

    /**
     * @param number $amount
     * @return $this
     * @throws \Exception
     */
    public function deposite($amount)
    {
        if (!$this->exists || $this->isDirty()) {
            throw new \Exception('You must save wallet before deposite the money');
        }
        $this->balance += $amount;

        $this->save();

        return $this;
    }
}