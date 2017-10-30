<?php

namespace Module\Billing\Tests\Unit\Entities;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Module\Billing\Entities\TransactionRobokassa;
use Tests\TestCase;

class TransactionRobokassaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function transaction_robokassa_belongs_to_user()
    {
        $transaction = factory(TransactionRobokassa::class)->create();

        $this->assertTrue($transaction->user instanceof User);
    }
}
