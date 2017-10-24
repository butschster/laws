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
                    'created_at' => '2013-09-13',
                    'rate' => 5.5,
                ],
                [
                    'created_at' => '2014-03-03',
                    'rate' => 7.0,
                ],
                [
                    'created_at' => '2014-04-28',
                    'rate' => 7.5,
                ],
                [
                    'created_at' => '2014-07-28',
                    'rate' => 8.0,
                ],
                [
                    'created_at' => '2014-11-05',
                    'rate' => 9.5,
                ],
                [
                    'created_at' => '2014-12-12',
                    'rate' => 10.5,
                ],
                [
                    'created_at' => '2014-12-16',
                    'rate' => 17.0,
                ],
                [
                    'created_at' => '2015-02-02',
                    'rate' => 15.0,
                ],
                [
                    'created_at' => '2015-03-16',
                    'rate' => 14.0,
                ],
                [
                    'created_at' => '2015-05-05',
                    'rate' => 12.5,
                ],
                [
                    'created_at' => '2015-06-16',
                    'rate' => 11.5,
                ],
                [
                    'created_at' => '2015-08-03',
                    'rate' => 11.0,
                ],
                [
                    'created_at' => '2016-06-14',
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
