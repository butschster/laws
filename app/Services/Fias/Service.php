<?php

namespace App\Services\Fias;

use Illuminate\Database\DatabaseManager;

class Service
{
    const LEVEL_STREET = 7;

    const DB_CONNECTION = 'fias';

    /**
     * @var \Illuminate\Database\Connection
     */
    private $db;

    /**
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->db = $databaseManager->connection(Service::DB_CONNECTION);
    }

    /**
     * @param string $guid
     *
     * @return array
     */
    public function searchByGuid(string $guid): array
    {

    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function searchByCode(string $id): array
    {

    }
}