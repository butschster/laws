<?php

namespace Module\Billing\Tests\Unit\Entities;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Module\Billing\Entities\Balance;
use Module\Billing\Entities\BalanceTransaction;
use Tests\TestCase;

class BalanceTransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function transactions_increase_balance()
    {
        $balance = new Balance;
        $balance->setState(100, Carbon::parse('-1 day'));
        $this->assertEquals(100, $balance->total());

        $transaction = BalanceTransaction::createInflow([
            'amount' => 199.22,
            'transactionable_type' => 'SomeTransactionObject',
            'transactionable_id' => 1,
        ]);

        $this->assertTrue($transaction instanceof BalanceTransaction);
        $this->assertTrue($transaction->isInflow());

        $this->assertEquals(299.22, $balance->total());
    }

    /** @test */
    function transactions_decrease_balance()
    {
        $balance = new Balance;
        $balance->setState(100, Carbon::parse('-1 day'));
        $this->assertEquals(100, $balance->total());

        $transaction = BalanceTransaction::createOutflow([
            'amount' => 50,
            'transactionable_type' => 'SomeTransactionObject',
            'transactionable_id' => 1,
        ]);

        $this->assertTrue($transaction instanceof BalanceTransaction);
        $this->assertFalse($transaction->isInflow());

        $this->assertEquals(50.00, $balance->total());
    }

    /** @test */
    function can_have_a_several_transactions_per_time()
    {
        $balance = new Balance;
        $balance->setState(100, Carbon::parse('-1 day'));

        $transactionA = BalanceTransaction::createInflow([
            'amount' => 10,
            'transactionable_type' => 'SomeTransactionObject',
            'transactionable_id' => 1,
        ]);

        $transactionB = BalanceTransaction::createOutflow([
            'amount' => 20,
            'transactionable_type' => 'SomeTransactionObject',
            'transactionable_id' => 1,
        ]);

        $transactionC = BalanceTransaction::createInflow([
            'amount' => 30,
            'transactionable_type' => 'SomeTransactionObject',
            'transactionable_id' => 1,
        ]);

        $this->assertEquals(120.00, $balance->total());
    }
}
