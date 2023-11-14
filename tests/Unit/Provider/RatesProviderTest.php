<?php

namespace Unit\Provider;

use App\Model\Currency\CurrencyInterface;
use App\Processor\ProcessorInterface;
use App\Provider\RatesProvider;
use PHPUnit\Framework\TestCase;

class RatesProviderTest extends TestCase
{
    public function testSupport(): void
    {
        $provider = new RatesProvider();
        $this->assertSame('rates', $provider::support());
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

        $provider = new RatesProvider();
        $provider->setProcessor($processor);
        $provider->setCurrencies(['USD' => $currency]);

        $this->assertSame([$currency], $provider->provide([
            [
                'currency' => 'dolar amerykański',
                'code' => 'USD',
                'mid' => 3.8191,
            ]
        ]));
    }

    public function testProvideWithNotExistingCurrency(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->never())
            ->method('process');

        $currency = $this->createMock(CurrencyInterface::class);

        $provider = new RatesProvider();
        $provider->setProcessor($processor);
        $provider->setCurrencies(['USD' => $currency]);

        $this->assertSame([], $provider->provide([
            [
                'currency' => 'euro',
                'code' => 'EUR',
                'mid' => 3.8191,
            ]
        ]));
    }

    public function testProvideWithNotExistingCode(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->never())
            ->method('process');

        $currency = $this->createMock(CurrencyInterface::class);

        $provider = new RatesProvider();
        $provider->setProcessor($processor);
        $provider->setCurrencies(['USD' => $currency]);

        $this->assertSame([], $provider->provide([
            [
                'currency' => 'dolar amerykański',
                'mid' => 3.8191,
            ]
        ]));
    }

    public function testSetProcessor(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);

        $provider = new RatesProvider();
        $provider->setProcessor($processor);

        $property = (new \ReflectionClass($provider))->getProperty('processor');
        $property->setAccessible(true);

        $this->assertSame($processor, $property->getValue($provider));
    }

    public function testSetCurrencies(): void
    {
        $currencies = ['USD' => $this->createMock(CurrencyInterface::class)];

        $provider = new RatesProvider();
        $provider->setCurrencies($currencies);

        $property = (new \ReflectionClass($provider))->getProperty('currencies');
        $property->setAccessible(true);

        $this->assertSame($currencies, $property->getValue($provider));
    }
}