<?php

namespace Unit\Provider;

use App\Model\Currency\CurrencyInterface;
use App\Processor\ProcessorInterface;
use App\Provider\HistoricalRatesProvider;
use PHPUnit\Framework\TestCase;

class HistoricalRatesProviderTest extends TestCase
{
    public function testSupport(): void
    {
        $provider = new HistoricalRatesProvider();
        $this->assertSame('historical_rates', $provider::support());
    }

    public function testProvide(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->once())
            ->method('process')
            ->with($this->isInstanceOf(CurrencyInterface::class), [
                'currency' => 'dolar amerykański',
                'code' => 'USD',
                'mid' => 3.8191,
            ]);

        $currency = $this->createMock(CurrencyInterface::class);
        $currency->expects($this->once())
            ->method('setName')
            ->with('dolar amerykański');

        $provider = new HistoricalRatesProvider();
        $provider->setProcessor($processor);
        $provider->setCurrencies(['USD' => $currency]);

        $this->assertSame([$currency], $provider->provide([
            'currency' => 'dolar amerykański',
            'code' => 'USD',
            'mid' => 3.8191,
        ]));
    }

    public function testProvideWithNotExistingCurrency(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->never())
            ->method('process');

        $currency = $this->createMock(CurrencyInterface::class);
        $currency->expects($this->never())
            ->method('setName');

        $provider = new HistoricalRatesProvider();
        $provider->setProcessor($processor);
        $provider->setCurrencies(['USD' => $currency]);

        $this->assertSame([], $provider->provide([
            'currency' => 'dolar amerykański',
            'code' => 'EUR',
            'mid' => 3.8191,
        ]));
    }

    public function testProvideWithEmptyCurrencies(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->never())
            ->method('process');

        $provider = new HistoricalRatesProvider();
        $provider->setProcessor($processor);
        $provider->setCurrencies([]);

        $this->assertSame([], $provider->provide([
            'currency' => 'dolar amerykański',
            'code' => 'EUR',
            'mid' => 3.8191,
        ]));
    }

    public function testProvideWithEmptyIterable(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->never())
            ->method('process');

        $provider = new HistoricalRatesProvider();
        $provider->setProcessor($processor);
        $provider->setCurrencies([]);

        $this->assertSame([], $provider->provide([]));
    }

    public function testProvideWithEmptyCode(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->never())
            ->method('process');

        $provider = new HistoricalRatesProvider();
        $provider->setProcessor($processor);
        $provider->setCurrencies([]);

        $this->assertSame([], $provider->provide([
            'currency' => 'dolar amerykański',
            'mid' => 3.8191,
        ]));
    }

    public function testProvideWithEmptyCurrency(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->never())
            ->method('process');

        $provider = new HistoricalRatesProvider();
        $provider->setProcessor($processor);
        $provider->setCurrencies([]);

        $this->assertSame([], $provider->provide([
            'code' => 'EUR',
            'mid' => 3.8191,
        ]));
    }

    public function testProvideWithEmptyMid(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->never())
            ->method('process');

        $provider = new HistoricalRatesProvider();
        $provider->setProcessor($processor);
        $provider->setCurrencies([]);

        $this->assertSame([], $provider->provide([
            'currency' => 'dolar amerykański',
            'code' => 'EUR',
        ]));
    }

    public function testSetProcessor(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);

        $provider = new HistoricalRatesProvider();
        $provider->setProcessor($processor);

        $property = (new \ReflectionClass($provider))->getProperty('processor');
        $property->setAccessible(true);

        $this->assertSame($processor, $property->getValue($provider));
    }

    public function testSetCurrencies(): void
    {
        $currencies = ['USD' => $this->createMock(CurrencyInterface::class)];

        $provider = new HistoricalRatesProvider();
        $provider->setCurrencies($currencies);

        $property = (new \ReflectionClass($provider))->getProperty('currencies');
        $property->setAccessible(true);

        $this->assertSame($currencies, $property->getValue($provider));
    }
}