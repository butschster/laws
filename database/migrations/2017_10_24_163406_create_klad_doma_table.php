<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKladDomaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kladr_doma', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('korp')->nullable();
            $table->string('socr')->nullable();
            $table->string('code', 19)->nullable();
            $table->integer('index')->nullable();
            $table->integer('gninmb')->nullable();
            $table->string('uno')->nullable();
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
        Schema::dropIfExists('kladr_doma');
    }
}
