<?php


namespace Module\Billing\Observers;


use Module\Billing\Entities\Balance;
use Module\Billing\Entities\BalanceTransaction;

class BalanceTransactionObserver
{
    /**
     * @var Balance
     */
    private $balance;

    public function __construct(Balance $balance)
    {

        $this->balance = $balance;
    }
    /**
     * @param BalanceTransaction $transaction
     */
    public function created(BalanceTransaction $transaction)
    {
        if ($transaction->isInflow()) {
            $this->balance->increase($transaction);
        } else {
            $this->balance->decrease($transaction);
        }
    }

    /**
     * @param BalanceTransaction $transaction
     */
    public function deleted(BalanceTransaction $transaction)
    {
        if ($transaction->isInflow()) {
            Balance::decrease($transaction);
        } else {
            Balance::increase($transaction);
        }
    }
}