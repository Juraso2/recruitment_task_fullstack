<?php

namespace App\Exception;

class CurrencyHistoryNotFoundException extends \Exception
{
    public function __construct(string $code, string $date)
    {
        parent::__construct(sprintf('Currency "%s" history for date "%s" not found', $code, $date));
    }
}