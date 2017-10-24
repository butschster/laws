<?php

namespace App\Exceptions;

use Throwable;

class RefinancingRateResponse extends \Exception
{
    /**
     * RefinancingRateResponse constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 10000, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}