<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedInvoiceStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        factory(\Module\Billing\Entities\InvoiceStatus::class)->create([
            'code' => 'new',
            'title' => 'Новый'
        ]);

        factory(\Module\Billing\Entities\InvoiceStatus::class)->create([
            'code' => 'completed',
            'title' => 'Оплаченый'
        ]);

        factory(\Module\Billing\Entities\InvoiceStatus::class)->create([
            'code' => 'canceled',
            'title' => 'Отмененный'
        ]);

        factory(\Module\Billing\Entities\InvoiceStatus::class)->create([
            'code' => 'processing',
            'title' => 'В обработке'
        ]);
        factory(\Module\Billing\Entities\InvoiceStatus::class)->create([
            'code' => 'failed',
            'title' => 'Не проведен'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
