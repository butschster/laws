<?php

namespace Module\Billing\Tests\Unit\Entities;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Module\Billing\Entities\Invoice;
use Module\Billing\Entities\Wallet;
use Module\Billing\Exceptions\WalletNotSavedException;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_deposit_money()
    {
        $wallet = factory(Wallet::class)->states('zero')->create();
        $this->assertEquals(0, $wallet->totalBalance());

        $wallet->deposit(12.34);

        $this->assertEquals(12.34, $wallet->totalBalance());
    }

    /** @test */
    function can_not_deposit_money_to_unsaved_wallet()
    {
        $wallet = factory(Wallet::class)->states('zero')->make();

        try {
            $wallet->deposit(123);
        } catch (WalletNotSavedException $e) {
            $this->assertEquals(0, $wallet->totalBalance());
            return;
        }

        $this->fail('Положили денег на несохраненный кошелек');
    }

    /** @test */
    function can_make_an_invoice()
    {
        $wallet = factory(Wallet::class)->states('zero')->create();

        $invoice = $wallet->createInvoice(1234.56);

        $this->assertTrue($invoice instanceof Invoice);
        $this->assertEquals($invoice->amount, 1234.56);
    }

    /** @test */
    function has_many_invoices()
    {
        $wallet = factory(Wallet::class)->states('zero')->create();

        $invoices = factory(Invoice::class, 3)->create(['wallet_id' => $wallet->id]);

        $this->assertEquals(3, $wallet->invoices->count());
    }
}
