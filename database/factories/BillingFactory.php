<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Module\Billing\Entities\Invoice;
use Module\Billing\Entities\InvoiceStatus;
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


$factory->define(Invoice::class, function (Faker $faker) {
    return [
        'wallet_id' => function(){
            return factory(Wallet::class)->create()->id;
        },
        'amount' => $faker->randomFloat(2, 0, 10000),
        'status_id' => InvoiceStatus::all()->pluck('id')->random(),
    ];
});

$factory->state(Invoice::class, 'new', function($faker){
    return [
        'status_id' => InvoiceStatus::where('code', InvoiceStatus::STATUS_NEW)->firstOrFail()->id,
    ];
});

$factory->state(Invoice::class, 'completed', function($faker){
    return [
        'status_id' => InvoiceStatus::where('code', InvoiceStatus::STATUS_COMPLETED)->firstOrFail()->id,
    ];
});

$factory->define(InvoiceStatus::class, function (Faker $faker) {
    return [
        'code' => $faker->word,
        'title' => $faker->word,
    ];
});