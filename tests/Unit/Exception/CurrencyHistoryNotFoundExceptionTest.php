<?php

namespace Unit\Exception;

use App\Exception\CurrencyHistoryNotFoundException;
use PHPUnit\Framework\TestCase;

class CurrencyHistoryNotFoundExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Currency "EUR" history for date "2019-01-01" not found');
        throw new CurrencyHistoryNotFoundException('EUR', '2019-01-01');
    }
}