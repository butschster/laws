<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedRegions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $regions = [
            [
                'id' => '1',
                'name' => 'Белгородская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '2',
                'name' => 'Брянская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '3',
                'name' => 'Владимирская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '4',
                'name' => 'Воронежская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '5',
                'name' => 'Ивановская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '6',
                'name' => 'Калужская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '7',
                'name' => 'Костромская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '8',
                'name' => 'Курская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '9',
                'name' => 'Липецкая область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '10',
                'name' => 'Московская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '11',
                'name' => 'Орловская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '12',
                'name' => 'Рязанская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '13',
                'name' => 'Смоленская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '14',
                'name' => 'Тамбовская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '15',
                'name' => 'Тверская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '16',
                'name' => 'Тульская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '17',
                'name' => 'Ярославская область',
                'federal_district_id' => '1',
            ],
            [
                'id' => '18',
                'name' => 'город Москва',
                'federal_district_id' => '1',
            ],
            [
                'id' => '20',
                'name' => 'Республика Карелия',
                'federal_district_id' => '2',
            ],
            [
                'id' => '21',
                'name' => 'Республика Коми',
                'federal_district_id' => '2',
            ],
            [
                'id' => '22',
                'name' => 'Архангельская область',
                'federal_district_id' => '2',
            ],
            [
                'id' => '23',
                'name' => 'Вологодская область',
                'federal_district_id' => '2',
            ],
            [
                'id' => '24',
                'name' => 'Калининградская область',
                'federal_district_id' => '2',
            ],
            [
                'id' => '25',
                'name' => 'Ленинградская область',
                'federal_district_id' => '2',
            ],
            [
                'id' => '26',
                'name' => 'Мурманская область',
                'federal_district_id' => '2',
            ],
            [
                'id' => '27',
                'name' => 'Новгородская область',
                'federal_district_id' => '2',
            ],
            [
                'id' => '28',
                'name' => 'Псковская область',
                'federal_district_id' => '2',
            ],
            [
                'id' => '29',
                'name' => 'город Санкт-Петербург',
                'federal_district_id' => '2',
            ],
            [
                'id' => '30',
                'name' => 'Ненецкий автономный округ',
                'federal_district_id' => '2',
            ],
            [
                'id' => '31',
                'name' => 'Республика Адыгея',
                'federal_district_id' => '3',
            ],
            [
                'id' => '32',
                'name' => 'Республика Калмыкия',
                'federal_district_id' => '3',
            ],
            [
                'id' => '86',
                'name' => 'Республика Крым',
                'federal_district_id' => '9',
            ],
            [
                'id' => '33',
                'name' => 'Краснодарский край',
                'federal_district_id' => '3',
            ],
            [
                'id' => '34',
                'name' => 'Астраханская область',
                'federal_district_id' => '3',
            ],
            [
                'id' => '35',
                'name' => 'Волгоградская область',
                'federal_district_id' => '3',
            ],
            [
                'id' => '36',
                'name' => 'Ростовская область',
                'federal_district_id' => '3',
            ],
            [
                'id' => '87',
                'name' => 'город Севастополь',
                'federal_district_id' => '9',
            ],
            [
                'id' => '38',
                'name' => 'Республика Дагестан',
                'federal_district_id' => '4',
            ],
            [
                'id' => '39',
                'name' => 'Республика Ингушетия',
                'federal_district_id' => '4',
            ],
            [
                'id' => '40',
                'name' => 'Кабардино-Балкарская Республика',
                'federal_district_id' => '4',
            ],
            [
                'id' => '41',
                'name' => 'Карачаево-Черкесская Республика',
                'federal_district_id' => '4',
            ],
            [
                'id' => '42',
                'name' => 'Республика Северная Осетия-Алания',
                'federal_district_id' => '4',
            ],
            [
                'id' => '43',
                'name' => 'Чеченская Республика',
                'federal_district_id' => '4',
            ],
            [
                'id' => '44',
                'name' => 'Ставропольский край',
                'federal_district_id' => '4',
            ],
            [
                'id' => '45',
                'name' => 'Республика Башкортостан',
                'federal_district_id' => '5',
            ],
            [
                'id' => '46',
                'name' => 'Республика Марий Эл',
                'federal_district_id' => '5',
            ],
            [
                'id' => '47',
                'name' => 'Республика Мордовия',
                'federal_district_id' => '5',
            ],
            [
                'id' => '48',
                'name' => 'Республика Татарстан',
                'federal_district_id' => '5',
            ],
            [
                'id' => '49',
                'name' => 'Удмуртская Республика',
                'federal_district_id' => '5',
            ],
            [
                'id' => '50',
                'name' => 'Чувашская Республика - Чувашия',
                'federal_district_id' => '5',
            ],
            [
                'id' => '51',
                'name' => 'Пермский край',
                'federal_district_id' => '5',
            ],
            [
                'id' => '52',
                'name' => 'Кировская область',
                'federal_district_id' => '5',
            ],
            [
                'id' => '53',
                'name' => 'Нижегородская область',
                'federal_district_id' => '5',
            ],
            [
                'id' => '54',
                'name' => 'Оренбургская область',
                'federal_district_id' => '5',
            ],
            [
                'id' => '55',
                'name' => 'Пензенская область',
                'federal_district_id' => '5',
            ],
            [
                'id' => '56',
                'name' => 'Самарская область',
                'federal_district_id' => '5',
            ],
            [
                'id' => '57',
                'name' => 'Саратовская область',
                'federal_district_id' => '5',
            ],
            [
                'id' => '58',
                'name' => 'Ульяновская область',
                'federal_district_id' => '5',
            ],
            [
                'id' => '59',
                'name' => 'Курганская область',
                'federal_district_id' => '6',
            ],
            [
                'id' => '60',
                'name' => 'Свердловская область',
                'federal_district_id' => '6',
            ],
            [
                'id' => '61',
                'name' => 'Тюменская область',
                'federal_district_id' => '6',
            ],
            [
                'id' => '62',
                'name' => 'Челябинская область',
                'federal_district_id' => '6',
            ],
            [
                'id' => '63',
                'name' => 'Ханты-Мансийский автономный округ - Югра',
                'federal_district_id' => '6',
            ],
            [
                'id' => '64',
                'name' => 'Ямало-Ненецкий автономный округ',
                'federal_district_id' => '6',
            ],
            [
                'id' => '65',
                'name' => 'Республика Алтай',
                'federal_district_id' => '7',
            ],
            [
                'id' => '66',
                'name' => 'Республика Бурятия',
                'federal_district_id' => '7',
            ],
            [
                'id' => '67',
                'name' => 'Республика Тыва',
                'federal_district_id' => '7',
            ],
            [
                'id' => '68',
                'name' => 'Республика Хакасия',
                'federal_district_id' => '7',
            ],
            [
                'id' => '69',
                'name' => 'Алтайский край',
                'federal_district_id' => '7',
            ],
            [
                'id' => '70',
                'name' => 'Забайкальский край',
                'federal_district_id' => '7',
            ],
            [
                'id' => '71',
                'name' => 'Красноярский край',
                'federal_district_id' => '7',
            ],
            [
                'id' => '72',
                'name' => 'Иркутская область',
                'federal_district_id' => '7',
            ],
            [
                'id' => '73',
                'name' => 'Кемеровская область',
                'federal_district_id' => '7',
            ],
            [
                'id' => '74',
                'name' => 'Новосибирская область',
                'federal_district_id' => '7',
            ],
            [
                'id' => '75',
                'name' => 'Омская область',
                'federal_district_id' => '7',
            ],
            [
                'id' => '76',
                'name' => 'Томская область',
                'federal_district_id' => '7',
            ],
            [
                'id' => '77',
                'name' => 'Республика Саха (Якутия)',
                'federal_district_id' => '8',
            ],
            [
                'id' => '78',
                'name' => 'Камчатский край',
                'federal_district_id' => '8',
            ],
            [
                'id' => '79',
                'name' => 'Приморский край',
                'federal_district_id' => '8',
            ],
            [
                'id' => '80',
                'name' => 'Хабаровский край',
                'federal_district_id' => '8',
            ],
            [
                'id' => '81',
                'name' => 'Амурская область',
                'federal_district_id' => '8',
            ],
            [
                'id' => '82',
                'name' => 'Магаданская область',
                'federal_district_id' => '8',
            ],
            [
                'id' => '83',
                'name' => 'Сахалинская область',
                'federal_district_id' => '8',
            ],
            [
                'id' => '84',
                'name' => 'Еврейская автономная область',
                'federal_district_id' => '8',
            ],
            [
                'id' => '85',
                'name' => 'Чукотский автономный округ',
                'federal_district_id' => '8',
            ],
        ];

        $now = now();

        $regions = array_map(function ($row) use ($now) {
            $row['created_at'] = $now;

            return $row;
        }, $regions);

        \DB::table((new \App\Region())->getTable())->insert($regions);
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
