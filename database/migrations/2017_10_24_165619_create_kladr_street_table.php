<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKladrStreetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kladr_street', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('socr', 10)->nullable();
            $table->string('code', 19)->nullable();
            $table->integer('index')->nullable();
            $table->integer('gninmb')->nullable();
            $table->integer('uno')->nullable();
            $table->string('ocatd', 11)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kladr_street');
    }
}
