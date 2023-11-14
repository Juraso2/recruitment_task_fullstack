<?php

namespace Unit\Exception;

use App\Exception\CurrencyNotSupportedException;
use PHPUnit\Framework\TestCase;

class CurrencyNotSupportedExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Currency with code "EUR" is not supported.');
        throw new CurrencyNotSupportedException('EUR');
    }
}