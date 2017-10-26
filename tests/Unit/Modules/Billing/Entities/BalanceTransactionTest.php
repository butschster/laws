<?php

namespace Tests\Unit\Modules\Billing\Entities;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Module\Billing\Entities\Balance;
use Module\Billing\Entities\BalanceTransaction;
use Module\Billing\Entities\Invoice;
use Module\Billing\Entities\InvoiceStatus;
use Module\Billing\Entities\Wallet;
use Module\Billing\Exceptions\WrongInvoiceStatusException;
use Tests\TestCase;

class BalanceTransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function transactions_increase_balance()
    {
        $balance = new Balance;
        $balance->setState(100);
        $this->assertEquals(100, $balance->total());

        $transaction = BalanceTransaction::createInflow([
            'amount' => 199.22,
            'transactionable_type' => 'SomeTransactionObject',
            'transactionable_id' => 1,
        ]);

        dd($transaction);

        $this->assertTrue($transaction instanceof BalanceTransaction);
        $this->assertTrue($transaction->isInflow());
        $this->assertEquals(299.22, $balance->total());
    }
}
