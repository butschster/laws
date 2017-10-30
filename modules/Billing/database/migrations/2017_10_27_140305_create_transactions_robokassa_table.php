<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Module\Billing\Entities\TransactionRobokassa;

class CreateTransactionsRobokassaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions_robokassa', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('invoice_id')->unique();
            $table->decimal('amount', 10, 2)->default(0);

            $table->string('payment_id')->nullable();
            $table->enum('status', [
                TransactionRobokassa::STATUS_NEW,
                TransactionRobokassa::STATUS_COMPLETED,
                TransactionRobokassa::STATUS_CANCELED
            ])->default(TransactionRobokassa::STATUS_NEW);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions_robokassa');
    }
}
