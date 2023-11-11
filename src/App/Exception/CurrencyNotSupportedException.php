<?php

namespace App\Exception;

class CurrencyNotSupportedException extends \Exception
{
    public function __construct(string $code)
    {
        parent::__construct(sprintf('Currency with code %s is not supported.', $code));
    }
}