<?php

namespace Unit\Exception;

use App\Exception\RatesWithDateNotFoundException;
use PHPUnit\Framework\TestCase;

class RatesWithDateNotFoundExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Rates for date "2019-01-01" not found');
        throw new RatesWithDateNotFoundException('2019-01-01');
    }
}