<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKladrSocrbaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kladr_socrbase', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('level')->nullable();
            $table->string('scname', 10)->nullable();
            $table->string('socrname', 50)->nullable();
            $table->integer('kod_t_st')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kladr_socrbase');
    }
}
