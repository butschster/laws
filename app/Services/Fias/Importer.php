<?php

namespace App\Services\Fias;

use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;
use XBase\Record;
use XBase\Table;

class Importer
{
    const TABLE_REGEXP = '/(?<table>[A-Z]+)[0-9]*\.DBF/i';
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * @param Filesystem $filesystem
     * @param DatabaseManager $db
     */
    public function __construct(Filesystem $filesystem, DatabaseManager $db)
    {
        $this->filesystem = $filesystem;
        $this->db = $db->connection(Service::DB_CONNECTION);
    }

    /**
     * @param SplFileInfo $file
     */
    public function import(SplFileInfo $file)
    {
        $table = $this->getTableName($file->getBasename());
        if ( !$table) {
            return;
        }

        $database = new Table($file->getPathname());

        $batch = 0;
        $data = [];

        while ($row = $database->nextRecord()) {

            $values = collect($row->getColumns())->keys()->mapWithKeys(function ($key) use ($row) {
                $value = $row->getString($key);

                switch ($row->getColumn($key)->type) {
                    case Record::DBFFIELD_TYPE_DATE:
                        try {
                            $value = Carbon::parse($value);
                        } catch (\Exception $e) {
                            $value = now();
                        }

                        break;
                    case Record::DBFFIELD_TYPE_NUMERIC:
                        $value = intval($value);
                        break;
                    default:
                        $value = $value ? trim(iconv('cp866', 'utf-8', $value)) : '';
                        break;
                }
                return [$key => $value];
            });

            $batch++;
            $data[] = $values->toArray();

            if ($batch == 1000) {
                $this->insertData($table, $data);

                $data = [];
                $batch = 0;
            }
        }

        $this->insertData($table, $data);
    }

    /**
     * @param string $fileName
     *
     * @return string|null
     */
    protected function getTableName(string $fileName)
    {
        $matches = [];
        preg_match(static::TABLE_REGEXP, $fileName, $matches);

        return array_get($matches, 'table');
    }

    /**
     * @param string $table
     * @param array $data
     */
    protected function insertData(string $table, array $data)
    {
        $this->db->table($table)->insert($data);
    }
}