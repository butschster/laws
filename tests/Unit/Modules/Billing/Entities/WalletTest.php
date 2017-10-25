<?php

namespace Tests\Unit\Modules\Billing\Entities;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Module\Billing\Entities\Wallet;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_deposit_money()
    {
        $wallet = factory(Wallet::class)->states('zero')->create();
        $this->assertEquals(0, $wallet->totalBalance());

        $wallet->deposite(12.34);

        $this->assertEquals(12.34, $wallet->totalBalance());
    }

    /** @test */
    function can_not_deposite_money_to_unsaved_wallet()
    {
        $wallet = factory(Wallet::class)->states('zero')->make();

        try {
            $wallet->deposite(123);
        } catch (\Exception $e) {
            $this->assertEquals(0, $wallet->totalBalance());
            return;
        }

        $this->fail('Положили денег на несохраненный кошелек');
    }
}
