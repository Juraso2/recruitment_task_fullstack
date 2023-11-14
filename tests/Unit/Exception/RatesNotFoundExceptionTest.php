<?php

namespace Unit\Exception;

use App\Exception\RatesNotFoundException;
use PHPUnit\Framework\TestCase;

class RatesNotFoundExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Rates not found');
        throw new RatesNotFoundException();
    }
}