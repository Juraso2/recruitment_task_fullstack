<?php

namespace App\Exception;

class WrongDateException extends \Exception
{
    public function __construct(string $date)
    {
        parent::__construct(sprintf('Provided date %s is not valid', $date));
    }
}