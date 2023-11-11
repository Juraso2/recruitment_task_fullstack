<?php

namespace App\Exception;

class RatesNotFoundException extends \Exception
{
    public function __construct()
    {
        $message = 'Rates not found';

        parent::__construct($message);
    }
}