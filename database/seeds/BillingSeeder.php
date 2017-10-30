<?php

use App\User;
use Illuminate\Database\Seeder;
use Module\Billing\Entities\TransactionRobokassa;
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

            factory(TransactionRobokassa::class)->states('new')->create(['user_id' => $user->id]);
            factory(TransactionRobokassa::class)->states('completed')->create(['user_id' => $user->id]);
            factory(TransactionRobokassa::class)->states('canceled')->create(['user_id' => $user->id]);
            factory(TransactionRobokassa::class, 10)->create(['user_id' => $user->id]);
        });

    }
}
