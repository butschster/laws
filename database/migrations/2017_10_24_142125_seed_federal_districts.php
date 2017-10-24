<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedFederalDistricts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $districts = [
            [
                'id' => 1,
                'name' => 'Центральный федеральный округ',
            ],
            [
                'id' => 2,
                'name' => 'Северо-Западный федеральный округ',
            ],
            [
                'id' => 3,
                'name' => 'Южный федеральный округ',
            ],
            [
                'id' => 4,
                'name' => 'Северо–Кавказский федеральный округ',
            ],
            [
                'id' => 5,
                'name' => 'Приволжский федеральный округ',
            ],
            [
                'id' => 6,
                'name' => 'Уральский федеральный округ',
            ],
            [
                'id' => 7,
                'name' => 'Сибирский федеральный округ',
            ],
            [
                'id' => 8,
                'name' => 'Дальневосточный федеральный округ',
            ],
            [
                'id' => 9,
                'name' => 'Крымский федеральный округ',
            ],
        ];

        $now = now();

        $districts = array_map(function ($row) use($now) {
            $row['created_at'] = $now;

            return $row;
        }, $districts);

        \DB::table((new \App\FederalDistrict())->getTable())->insert($districts);
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
