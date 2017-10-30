<?php

namespace Tests\Feature\Profile;

use App\User;
use Module\Billing\Entities\Wallet;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guests_cannot_see_wallet_page()
    {
//        $this->withoutExceptionHandling();
        $response = $this->get(route('profile.wallet'));

        $response->assertStatus(302);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    function users_can_see_wallet_page()
    {
        $this->withoutExceptionHandling();
        $wallet = factory(Wallet::class)->create();

        $response = $this->actingAs($wallet->user)->get(route('profile.wallet'));

        $response->assertStatus(200);
    }

    /** @test */
    function user_can_see_wallet_balance()
    {
        $this->withoutExceptionHandling();
        $wallet = factory(Wallet::class)->states('zero')->create();
        $wallet->deposit(123.45);

        $response = $this->actingAs($wallet->user)->get(route('profile.wallet'));

        $response->assertStatus(200);
        $response->assertSee('123.45');

    }
}
