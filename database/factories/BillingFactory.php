<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Module\Billing\Entities\Invoice;
use Module\Billing\Entities\InvoiceStatus;
use Module\Billing\Entities\TransactionRobokassa;
use Module\Billing\Entities\Wallet;

$factory->define(Wallet::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'balance' => $faker->randomFloat(2, 0, 10000)
    ];
});

$factory->state(Wallet::class, 'zero', function ($faker) {
    return [
        'balance' => 0,
    ];
});


$factory->define(Invoice::class, function (Faker $faker) {
    return [
        'wallet_id' => function () {
            return factory(Wallet::class)->create()->id;
        },
        'amount' => $faker->randomFloat(2, 0, 10000),
        'status_id' => InvoiceStatus::all()->pluck('id')->random(),
    ];
});

$factory->state(Invoice::class, 'new', function ($faker) {
    return [
        'status_id' => InvoiceStatus::where('code', InvoiceStatus::STATUS_NEW)->firstOrFail()->id,
    ];
});

$factory->state(Invoice::class, 'completed', function ($faker) {
    return [
        'status_id' => InvoiceStatus::where('code', InvoiceStatus::STATUS_COMPLETED)->firstOrFail()->id,
    ];
});

$factory->state(Invoice::class, 'canceled', function ($faker) {
    return [
        'status_id' => InvoiceStatus::where('code', InvoiceStatus::STATUS_CANCELED)->firstOrFail()->id,
    ];
});

$factory->define(InvoiceStatus::class, function (Faker $faker) {
    return [
        'code' => $faker->word,
        'title' => $faker->word,
    ];
});

$factory->define(TransactionRobokassa::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'invoice_id' => function (array $post) {
            return factory(Invoice::class)->create([
                'wallet_id' => factory(Wallet::class)->create(['user_id' => $post['user_id']])->id,
            ])->id;
        },
        'amount' => $faker->randomFloat(2, 0, 10000),
        'status' => function (array $post) {
            return InvoiceStatus::convertStatusToTransationStatus(Invoice::find($post['invoice_id'])->status->code);
        },
    ];
});

$factory->state(TransactionRobokassa::class, 'new', function ($faker) {
    return [
        'status' => TransactionRobokassa::STATUS_NEW,
        'invoice_id' => function (array $post) {
            return factory(Invoice::class)->states('new')->create([
                'wallet_id' => factory(Wallet::class)->create(['user_id' => $post['user_id']])->id,
            ])->id;
        },
    ];
});

$factory->state(TransactionRobokassa::class, 'completed', function ($faker) {
    return [
        'status' => TransactionRobokassa::STATUS_COMPLETED,
        'invoice_id' => function (array $post) {
            return factory(Invoice::class)->states('completed')->create([
                'wallet_id' => factory(Wallet::class)->create(['user_id' => $post['user_id']])->id,
            ])->id;
        },
    ];
});

$factory->state(TransactionRobokassa::class, 'canceled', function ($faker) {
    return [
        'status' => TransactionRobokassa::STATUS_CANCELED,
        'invoice_id' => function (array $post) {
            return factory(Invoice::class)->states('canceled')->create([
                'wallet_id' => factory(Wallet::class)->create(['user_id' => $post['user_id']])->id,
            ])->id;
        },
    ];
});