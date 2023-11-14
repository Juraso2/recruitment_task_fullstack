<?php

namespace Integration\Service;

use App\Exception\RatesWithDateNotFoundException;
use App\Exception\WrongDateException;
use App\Model\Currency\CurrencyInterface;
use App\Service\CurrencyService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CurrencyServiceTest extends KernelTestCase
{
    public function testFetchCurrenciesWillReturnArray(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $currencies = $container->get(CurrencyService::class)->fetchCurrencies();

        $this->assertIsArray($currencies);
    }

    public function testFetchCurrenciesWillReturnArrayWithCurrencyInterface(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $currencies = $container->get(CurrencyService::class)->fetchCurrencies();

        $this->assertInstanceOf(CurrencyInterface::class, $currencies[0]);
    }

    public function testFetchCurrenciesWillReturnArrayWithCurrencyInterfaceWithCode(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $currencies = $container->get(CurrencyService::class)->fetchCurrencies();

        $this->assertNotEmpty($currencies[0]->getCode());
    }

    public function testFetchCurrenciesWithDateWillReturnArray(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $currencies = $container->get(CurrencyService::class)->fetchCurrenciesWithDate('2023-11-10');

        $this->assertIsArray($currencies);
    }

    public function testFetchCurrenciesWithDateWillReturnArrayWithCurrencyInterface(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $currencies = $container->get(CurrencyService::class)->fetchCurrenciesWithDate('2023-11-10');

        $this->assertInstanceOf(CurrencyInterface::class, $currencies[0]);
    }

    public function testFetchCurrenciesWithDateWillReturnArrayWithCurrencyInterfaceWithCode(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $currencies = $container->get(CurrencyService::class)->fetchCurrenciesWithDate('2023-11-10');

        $this->assertNotEmpty($currencies[0]->getCode());
    }

    public function testFetchCurrenciesWithDateWillThrowRatesWithDateNotFoundException(): void
    {
        $this->expectException(RatesWithDateNotFoundException::class);
        $this->expectExceptionMessage('Rates for date "2023-11-11" not found');

        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $container->get(CurrencyService::class)->fetchCurrenciesWithDate('2023-11-11');
    }

    public function testFetchCurrencyHistoryWillReturnCurrencyInterface(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $currency = $container->get(CurrencyService::class)->fetchCurrencyHistory('USD', '2020-11-10');

        $this->assertInstanceOf(CurrencyInterface::class, $currency);
    }

    public function testFetchCurrencyHistoryWillReturnCurrencyInterfaceWithCode(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $currency = $container->get(CurrencyService::class)->fetchCurrencyHistory('USD', '2020-11-10');

        $this->assertEquals('USD', $currency->getCode());
    }

    public function testFetchCurrencyHistoryWithWrongDateWillThrowWrongDateException(): void
    {
        $this->expectException(WrongDateException::class);
        $this->expectExceptionMessage('Provided date "9999-99-99" is not valid');

        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $container->get(CurrencyService::class)->fetchCurrencyHistory('USD', '9999-99-99');
    }

    public function testFetchCurrencyHistoryWithWrongCodeWillThrowCurrencyHistoryNotFoundException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Currency "XXX" history for date "2020-11-10" not found');

        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $container->get(CurrencyService::class)->fetchCurrencyHistory('XXX', '2020-11-10');
    }
}