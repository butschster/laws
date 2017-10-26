<?php

namespace Module\Billing\Entities;

use Illuminate\Database\Eloquent\Model;
use Module\Billing\Exceptions\WalletNotSavedException;

class Wallet extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_wallets';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return float
     */
    public function totalBalance()
    {
        return $this->balance;
    }

    /**
     * @return float
     */
    public function freshBalance()
    {
        return static::find($this->id)->totalBalance();
    }

    /**
     * @param number $amount
     * @return $this
     * @throws \Exception
     */
    public function deposit($amount)
    {
        if (!$this->exists || $this->isDirty()) {
            throw new WalletNotSavedException();
        }
        $this->balance += $amount;

        $this->save();

        return $this;
    }

    /**
     * @param float|integer $amount
     * @return \Module\Billing\Entities\Invoice
     */
    public function createInvoice($amount)
    {
        return Invoice::createForWallet($amount, $this);
    }

    /**
     * Получение списка выставленных счетов на оплату
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}