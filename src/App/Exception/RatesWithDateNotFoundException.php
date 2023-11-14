<?php

namespace App\Exception;

class RatesWithDateNotFoundException extends \Exception
{
    public function __construct(string $date)
    {
        $message = sprintf('Rates for date "%s" not found', $date);

        parent::__construct($message);
    }
}