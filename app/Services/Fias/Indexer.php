<?php

namespace App\Services\Fias;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;

class Indexer
{

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * @var string
     */
    private $table = 'ADDROB';

    /**
     * @param DatabaseManager $db
     */
    public function __construct(DatabaseManager $db)
    {
        $this->db = $db->connection(Service::DB_CONNECTION);
    }

    public function index(int $level)
    {
        $totalRecords = $this->db->query()->from($this->table)->where('aolevel', $level)->count();

        $query = $this->db->query()->select('ao0.aoguid', 'ao0.aolevel')
            ->from($this->table.' as ao0')
            ->where('ao0.aolevel', $level);

        $query->join("SOCRBASE as sn", function ($join) {
            $join
                ->on("sn.scname", '=', "ao0.shortname")
                ->on("sn.level", '=', "ao0.aolevel");
        });

        if ($level > 1) {
            $shortName = 'concat(sn.socrname';
            $fullName = 'concat(ao0.shortname, " ", ao0.formalname';

            foreach (range(1, $level) as $i) {
                $shortName .= ', ", ", sn'.$i.'.socrname';
                $fullName .= ', ", ", ao'.$i.'.shortname, " ", ao'.$i.'.formalname';
            }

            $shortName .= ')';
            $fullName .= ')';

            foreach (range(1, $level) as $i) {
                $parentI = $i - 1;

                $query->join("ADDROB as ao{$i}", function ($join) use ($i, $parentI) {
                    $join->on("ao{$i}.parentguid", '=', "ao{$parentI}.aoguid");
                });
                $query->join("SOCRBASE as sn{$i}", function ($join) use ($i) {
                    $join
                        ->on("sn{$i}.scname", '=', "ao{$i}.shortname")
                        ->on("sn{$i}.level", '=', "ao{$i}.aolevel");
                });
            }

        } else {
            $shortName = 'sn.socrname';
            $fullName = 'concat(ao0.shortname, " ", ao0.formalname)';
        }

        $query
            ->selectRaw("{$shortName} as scname")
            ->selectRaw("{$fullName} as fullname");

        $batchSize = 500;
        $skip = 0;

        while ($totalRecords > 0) {
            $this->indexRows(
                $query->take($batchSize)->skip($skip)->get()
            );

            $skip += $batchSize;
            $totalRecords -= $batchSize;
        }
    }

    /**
     * @param Collection $rows
     */
    protected function indexRows(Collection $rows)
    {
        $rows = $rows->map(function ($row) {
            return (array) $row;
        });

        $this->db->query()->from('fias_index')->insert($rows->toArray());
    }
}