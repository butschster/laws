<?php

use App\User;
use Illuminate\Database\Seeder;
use Module\Billing\Entities\Wallet;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        $users->each(function ($user) {
            factory(Wallet::class)->create([
                'user_id' => $user->id,
            ]);
        });

    }
}
