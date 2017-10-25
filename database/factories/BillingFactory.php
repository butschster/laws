<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Module\Billing\Entities\Wallet;

$factory->define(Wallet::class, function (Faker $faker) {
    return [
        'user_id' => function(){
            return factory(User::class)->create()->id;
        },
        'balance' => $faker->randomFloat(2, 0, 10000)
    ];
});

$factory->state(Wallet::class, 'zero', function($faker){
    return [
        'balance' => 0,
    ];
});

