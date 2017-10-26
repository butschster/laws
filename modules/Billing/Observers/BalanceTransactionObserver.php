<?php


namespace Modules\Billing\Observers;


use Module\Billing\Entities\BalanceTransaction;

class BalanceTransactionObserver
{
    /**
     * @param BalanceTransaction $transaction
     */
    public function created(BalanceTransaction $transaction)
    {
        dd('zz');
        if ($transaction->isInflow()) {
            Balance::increase($transaction);
        } else {
            Balance::decrease($transaction);
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