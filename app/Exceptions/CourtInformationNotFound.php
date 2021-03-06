<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CourtInformationNotFound extends Exception
{

    /**
     * @param string $courtCode
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $courtCode, $code = 0, \Throwable $previous = null)
    {
        parent::__construct("Суд с кодом [{$courtCode}] не найден.", $code, $previous);
    }
}
