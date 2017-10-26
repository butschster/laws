<?php

namespace Tests\Unit\Modules\Billing\Entities;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rules\In;
use Module\Billing\Entities\Invoice;
use Module\Billing\Entities\Wallet;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_create_invoice_for_wallet()
    {
        $wallet = factory(Wallet::class)->create();

        $invoice = Invoice::createForWallet(12.34, $wallet);

        $this->assertEquals(12.34, $invoice->amount);
        $this->assertTrue($invoice->wallet->is($wallet));
    }

    /** @test */
    function invoice_for_wallet_created_with_new_status()
    {
        $wallet = factory(Wallet::class)->create();

        $invoice = Invoice::createForWallet(12.34, $wallet);

        $this->assertEquals('new', $invoice->status->code);
    }

    /** @test */
    function pay_invoice_deposit_wallet_balance()
    {
        $wallet = factory(Wallet::class)->states('zero')->create();
        $this->assertEquals(0, $wallet->totalBalance());

        $invoice = Invoice::createForWallet(12.34, $wallet);
        $invoice->pay();

        $this->assertEquals(12.34, $wallet->freshBalance());

    }
}
