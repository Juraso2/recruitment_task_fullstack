<?php

namespace Unit\Service;

use App\Exception\CurrencyHistoryNotFoundException;
use App\Exception\RatesNotFoundException;
use App\Exception\RatesWithDateNotFoundException;
use App\Exception\WrongDateException;
use App\Factory\ProcessorFactoryInterface;
use App\Factory\ProviderFactoryInterface;
use App\Helper\NbpApiHelper;
use App\Http\ApiClientInterface;
use App\Model\Currency\CurrencyInterface;
use App\Processor\ProcessorInterface;
use App\Provider\ProviderInterface;
use App\Service\CurrencyService;
use PHPUnit\Framework\TestCase;

class CurrencyServiceTest extends TestCase
{
    private $currencies;
    private $nbpApiHelper;
    private $apiClient;
    private $providerFactory;
    private $provider;
    private $processorFactory;
    private $processor;

    public function setUp(): void
    {
        $this->currencies = [
            [
                'currency' => 'dolar amerykański',
                'code' => 'USD',
                'mid' => 3.784,
            ],
            [
                'currency' => 'euro',
                'code' => 'EUR',
                'mid' => 4.292,
            ]
        ];
        $this->nbpApiHelper = $this->createMock(NbpApiHelper::class);
        $this->apiClient = $this->createMock(ApiClientInterface::class);
        $this->providerFactory = $this->createMock(ProviderFactoryInterface::class);
        $this->provider = $this->createMock(ProviderInterface::class);
        $this->processorFactory = $this->createMock(ProcessorFactoryInterface::class);
        $this->processor = $this->createMock(ProcessorInterface::class);
    }

    public function testFetchCurrencies(): void
    {
        $this->nbpApiHelper->expects($this->once())
            ->method('getApiUrl')
            ->willReturn('http://api.nbp.pl/api/exchangerates/tables/A/');

        $this->apiClient->expects($this->once())
            ->method('fetch')
            ->with('http://api.nbp.pl/api/exchangerates/tables/A/')
            ->willReturn([
                [
                    'table' => 'A',
                    'no' => '001/A/NBP/2020',
                    'effectiveDate' => '2020-01-02',
                    'rates' => $this->currencies
                ]
            ]);

        $this->provider->expects($this->once())
            ->method('provide')
            ->willReturn($this->currencies);

        $this->providerFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->provider);

        $this->processorFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->processor);

        $currencies = (new CurrencyService(
            $this->currencies,
            $this->nbpApiHelper,
            $this->apiClient,
            $this->providerFactory,
            $this->processorFactory
        ))->fetchCurrencies();

        $this->assertEquals($this->currencies, $currencies);
    }

    public function testFetchCurrenciesWillThrowException(): void
    {
        $this->nbpApiHelper->expects($this->once())
            ->method('getApiUrl')
            ->willReturn('http://api.nbp.pl/api/exchangerates/tables/A/');

        $this->apiClient->expects($this->once())
            ->method('fetch')
            ->with('http://api.nbp.pl/api/exchangerates/tables/A/')
            ->willThrowException(new \Exception());

        $this->providerFactory->expects($this->never())
            ->method('create');

        $this->processorFactory->expects($this->never())
            ->method('create');

        $this->expectException(RatesNotFoundException::class);
        $this->expectExceptionMessage('Rates not found');

        (new CurrencyService(
            $this->currencies,
            $this->nbpApiHelper,
            $this->apiClient,
            $this->providerFactory,
            $this->processorFactory
        ))->fetchCurrencies();
    }

    public function testFetchCurrenciesWithDate(): void
    {
        $this->nbpApiHelper->expects($this->once())
            ->method('getApiUrlWithDate')
            ->with('2020-01-02')
            ->willReturn('http://api.nbp.pl/api/exchangerates/tables/A/2020-01-02/');

        $this->apiClient->expects($this->once())
            ->method('fetch')
            ->with('http://api.nbp.pl/api/exchangerates/tables/A/2020-01-02/')
            ->willReturn([
                [
                    'table' => 'A',
                    'no' => '001/A/NBP/2020',
                    'effectiveDate' => '2020-01-02',
                    'rates' => $this->currencies
                ]
            ]);

        $this->provider->expects($this->once())
            ->method('provide')
            ->willReturn($this->currencies);

        $this->providerFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->provider);

        $this->processorFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->processor);

        $currencies = (new CurrencyService(
            $this->currencies,
            $this->nbpApiHelper,
            $this->apiClient,
            $this->providerFactory,
            $this->processorFactory
        ))->fetchCurrenciesWithDate('2020-01-02');

        $this->assertEquals($this->currencies, $currencies);
    }

    public function testFetchCurrenciesWithDateWillThrowException(): void
    {
        $this->nbpApiHelper->expects($this->once())
            ->method('getApiUrlWithDate')
            ->with('2020-01-02')
            ->willReturn('http://api.nbp.pl/api/exchangerates/tables/A/2020-01-02/');

        $this->apiClient->expects($this->once())
            ->method('fetch')
            ->with('http://api.nbp.pl/api/exchangerates/tables/A/2020-01-02/')
            ->willThrowException(new \Exception());

        $this->providerFactory->expects($this->never())
            ->method('create');

        $this->processorFactory->expects($this->never())
            ->method('create');

        $this->expectException(RatesWithDateNotFoundException::class);
        $this->expectExceptionMessage('Rates for date "2020-01-02" not found');

        (new CurrencyService(
            $this->currencies,
            $this->nbpApiHelper,
            $this->apiClient,
            $this->providerFactory,
            $this->processorFactory
        ))->fetchCurrenciesWithDate('2020-01-02');
    }

    public function testFetchCurrencyHistory(): void
    {
        $this->nbpApiHelper->expects($this->once())
            ->method('getApiUrlWithDateRangeAndCode')
            ->with('USD', '2019-12-28', '2020-01-12')
            ->willReturn('http://api.nbp.pl/api/exchangerates/rates/A/USD/2020-01-01/2020-01-12/');

        $this->apiClient->expects($this->once())
            ->method('fetch')
            ->with('http://api.nbp.pl/api/exchangerates/rates/A/USD/2020-01-01/2020-01-12/')
            ->willReturn([
                'table' => 'A',
                'currency' => 'dolar amerykański',
                'code' => 'USD',
                'rates' => [
                    [
                        'no' => '001/A/NBP/2020',
                        'effectiveDate' => '2020-01-02',
                        'mid' => 3.784,
                    ],
                ]
            ]);

        $currencyMock = $this->createMock(CurrencyInterface::class);

        $this->provider->expects($this->once())
            ->method('provide')
            ->willReturn([
                $currencyMock
            ]);

        $this->providerFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->provider);

        $this->processorFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->processor);

        $currency = (new CurrencyService(
            $this->currencies,
            $this->nbpApiHelper,
            $this->apiClient,
            $this->providerFactory,
            $this->processorFactory
        ))->fetchCurrencyHistory('USD', '2020-01-12');

        $this->assertEquals($currencyMock, $currency);
    }

    public function testFetchCurrencyHistoryWillThrowException(): void
    {
        $this->nbpApiHelper->expects($this->once())
            ->method('getApiUrlWithDateRangeAndCode')
            ->with('USD', '2019-12-28', '2020-01-12')
            ->willReturn('http://api.nbp.pl/api/exchangerates/rates/A/USD/2020-01-01/2020-01-12/');

        $this->apiClient->expects($this->once())
            ->method('fetch')
            ->with('http://api.nbp.pl/api/exchangerates/rates/A/USD/2020-01-01/2020-01-12/')
            ->willThrowException(new \Exception());

        $this->providerFactory->expects($this->never())
            ->method('create');

        $this->processorFactory->expects($this->never())
            ->method('create');

        $this->expectException(CurrencyHistoryNotFoundException::class);
        $this->expectExceptionMessage('Currency "USD" history for date "2020-01-12" not found');

        (new CurrencyService(
            $this->currencies,
            $this->nbpApiHelper,
            $this->apiClient,
            $this->providerFactory,
            $this->processorFactory
        ))->fetchCurrencyHistory('USD', '2020-01-12');
    }

    public function testFetchCurrencyHistoryWillThrowExceptionWhenWrongDate(): void
    {
        $this->nbpApiHelper->expects($this->never())
            ->method('getApiUrlWithDateRangeAndCode');

        $this->apiClient->expects($this->never())
            ->method('fetch');

        $this->providerFactory->expects($this->never())
            ->method('create');

        $this->processorFactory->expects($this->never())
            ->method('create');

        $this->expectException(WrongDateException::class);
        $this->expectExceptionMessage('Provided date "9999-99-99');

        (new CurrencyService(
            $this->currencies,
            $this->nbpApiHelper,
            $this->apiClient,
            $this->providerFactory,
            $this->processorFactory
        ))->fetchCurrencyHistory('USD', '9999-99-99');
    }
}