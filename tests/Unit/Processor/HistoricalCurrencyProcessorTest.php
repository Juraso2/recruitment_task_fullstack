<?php

namespace Unit\Processor;

use App\Model\Currency\AbstractCurrency;
use App\Model\Currency\CurrencyHistoryInterface;
use App\Model\Currency\CurrencyInterface;
use App\Processor\HistoricalCurrencyProcessor;
use PHPUnit\Framework\TestCase;

class HistoricalCurrencyProcessorTest extends TestCase
{
    public function testSupport(): void
    {
        $processor = new HistoricalCurrencyProcessor();
        $this->assertSame('historical_rates', $processor::support());
    }

    public function testProcess(): void
    {
        $currency = $this->getMockBuilder(AbstractCurrency::class)
            ->onlyMethods(['addHistory'])
            ->getMockForAbstractClass();

        $currency->expects($this->exactly(2))
            ->method('addHistory')
            ->withConsecutive(
                [$this->isInstanceOf(CurrencyHistoryInterface::class)],
                [$this->isInstanceOf(CurrencyHistoryInterface::class)]
            );

        $processor = new HistoricalCurrencyProcessor();
        $processor->process($currency, [
            'table' => 'A',
            'currency' => 'dolar amerykański',
            'code' => 'USD',
            'rates' => [
                [
                    'no' => '248/A/NBP/2020',
                    'effectiveDate' => '2020-12-31',
                    'mid' => 3.8191,
                ],
                [
                    'no' => '247/A/NBP/2020',
                    'effectiveDate' => '2020-12-30',
                    'mid' => 3.8021,
                ],
            ],
        ]);
    }
}