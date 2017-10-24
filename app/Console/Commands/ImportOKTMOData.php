<?php

namespace App\Console\Commands;

use App\FederalDistrict;
use App\Region;
use Illuminate\Console\Command;

class ImportOKTMOData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:oktmo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $federalDistricts = [];
        $regions = [];

        foreach ($this->nextRow() as $item) {
            $federalDistricts[$item['FederalDistrictID']] = [
                'id' => $item['FederalDistrictID'],
                'name' => $item['FederalDistrictName'],
            ];

            $regions[$item['RegionID']] = [
                'id' => $item['RegionID'],
                'name' => $item['RegionName'],
                'federal_district_id' => $item['FederalDistrictID'],
            ];
        }

        \DB::table((new FederalDistrict())->getTable())->insert($federalDistricts);
        \DB::table((new Region())->getTable())->insert($regions);
    }

    protected function nextRow()
    {
        $handler = fopen(storage_path('app/oktmo/Oktmo.csv'), 'r');
        $headers = array_map('trim', fgetcsv($handler, 4096, ';'));

        while ( !feof($handler)) {
            $row = array_map('trim', (array)fgetcsv($handler, 4096, ';'));
            if (count($headers) !== count($row)) {
                continue;
            }
            $row = array_combine($headers, $row);
            yield $row;
        }
    }
}
