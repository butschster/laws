<?php

namespace Tests\Unit\Modules\Billing\Entities;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Module\Billing\Entities\Invoice;
use Module\Billing\Entities\TransactionRobokassa;
use Module\Billing\Entities\Wallet;
use Module\Billing\Exceptions\WalletNotSavedException;
use Tests\TestCase;

class TransactionRobokassaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function transaction_robokassa_belongs_to_user()
    {
        $transaction = factory(TransactionRobokassa::class, 50)->create();
    }
}
