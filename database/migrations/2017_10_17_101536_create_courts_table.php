<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('name');
            $table->string('type');
            $table->string('url')->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('email')->nullable();

            $table->string('region');
            $table->text('address');
            $table->string('code', 15);

            $table->string('lon', 20);
            $table->string('lat', 20);

            $table->date('synced_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courts');
    }
}
