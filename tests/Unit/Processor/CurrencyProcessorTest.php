<?php

namespace Unit\Processor;

use App\Model\Currency\CurrencyInterface;
use App\Processor\CurrencyProcessor;
use PHPUnit\Framework\TestCase;

class CurrencyProcessorTest extends TestCase
{
    public function testSupport(): void
    {
        $processor = new CurrencyProcessor();
        $this->assertSame('rates', $processor::support());
    }

    public function testProcess(): void
    {
        $currency = $this->createMock(CurrencyInterface::class);
        $currency->expects($this->once())
            ->method('setName')
            ->with('dolar amerykański');
        $currency->expects($this->once())
            ->method('setExchangeRate')
            ->with(3.8191);
        $currency->expects($this->once())
            ->method('setSellRate')
            ->with(3.8191);
        $currency->expects($this->once())
            ->method('setPurchaseRate')
            ->with(3.8191);

        $processor = new CurrencyProcessor();
        $processor->process($currency, [
            'currency' => 'dolar amerykański',
            'code' => 'USD',
            'mid' => 3.8191,
        ]);
    }
}