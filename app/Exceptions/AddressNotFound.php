<?php

namespace App\Exceptions;

use Throwable;

class AddressNotFound extends \Exception
{
    /**
     * @param string $address
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $address, $code = 0, Throwable $previous = null)
    {
        parent::__construct("Адерс [{$address}] не найден.", $code, $previous);
    }
}