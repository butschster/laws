<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedRefinancingRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table((new \App\RefinancingRate())->getTable())
            ->insert([
                [
                    'created_at' => '2016-08-01',
                    'rate' => 10.5,
                ],
                [
                    'created_at' => '2016-09-19',
                    'rate' => 10.0,
                ],
                [
                    'created_at' => '2017-03-27',
                    'rate' => 9.75,
                ],
                [
                    'created_at' => '2017-05-02',
                    'rate' => 9.25,
                ],
                [
                    'created_at' => '2017-06-19',
                    'rate' => 9.0,
                ],
                [
                    'created_at' => '2017-09-18',
                    'rate' => 8.5,
                ],
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
