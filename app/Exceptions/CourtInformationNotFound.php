<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CourtInformationNotFound extends Exception
{

    /**
     * @param string $courtCode
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $courtCode, $code = 0, Throwable $previous = null)
    {
        parent::__construct("Court with code [{$courtCode}] not found.", $code, $previous);
    }
}
