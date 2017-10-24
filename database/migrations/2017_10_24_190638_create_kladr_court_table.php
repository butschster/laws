<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKladrCourtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kladr_court', function (Blueprint $table) {
            $table->increments('id');

            $table->string('region_fias_id', 36)->nullable();
            $table->string('region_kladr_id', 19)->nullable();
            $table->string('area_fias_id', 36)->nullable();
            $table->string('area_kladr_id', 19)->nullable();
            $table->string('city_fias_id', 36)->nullable();
            $table->string('city_kladr_id', 19)->nullable();
            $table->string('settlement_fias_id', 36)->nullable();
            $table->string('settlement_kladr_id', 19)->nullable();
            $table->string('street_fias_id', 36)->nullable();
            $table->string('street_kladr_id', 19)->nullable();
            $table->string('house_fias_id', 36)->nullable();
            $table->string('house_kladr_id', 19)->nullable();

            $table->string('fias_id', 36)->nullable();
            $table->string('kladr_id', 19)->nullable();

            $table->unsignedInteger('court_id')->unique();

            $table->foreign('court_id')
                ->references('id')
                ->on('courts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kladr_court');
    }
}
