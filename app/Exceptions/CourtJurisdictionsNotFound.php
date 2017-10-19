<?php

namespace App\Exceptions;

use Exception;

class CourtJurisdictionsNotFound extends Exception
{

    /**
     * @param string $courtCode
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $courtCode, $code = 0, \Throwable $previous = null)
    {
        parent::__construct("Подсудности для суда с кодом [{$courtCode}] не найдены.", $code, $previous);
    }
}
